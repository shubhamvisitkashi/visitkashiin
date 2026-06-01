<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertImagesToWebP extends Command
{
    protected $signature   = 'images:webp {--quality=82} {--dir=} {--force}';
    protected $description = 'Convert PNG/JPG images to WebP format (originals preserved)';

    private array $dirs = [
        'backend/admin/hero_slides',
        'backend/admin/website_setup',
        'frontend/images',
    ];

    public function handle(): int
    {
        if (!function_exists('imagewebp')) {
            $this->error('GD WebP support not available.');
            return 1;
        }

        $quality = (int) $this->option('quality');
        $force   = $this->option('force');
        $dir     = $this->option('dir');

        $dirs = $dir ? [trim($dir, '/')] : $this->dirs;

        $converted = 0;
        $skipped   = 0;
        $saved     = 0;

        foreach ($dirs as $relDir) {
            $fullDir = public_path($relDir);
            if (!is_dir($fullDir)) continue;

            $files = glob($fullDir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
            foreach ($files as $file) {
                $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

                if (!$force && file_exists($webpPath)) {
                    $skipped++;
                    continue;
                }

                $img = $this->loadImage($file);
                if (!$img) continue;

                imagewebp($img, $webpPath, $quality);
                imagedestroy($img);

                $origSize  = filesize($file);
                $webpSize  = filesize($webpPath);
                $pct       = $origSize > 0 ? round(($origSize - $webpSize) / $origSize * 100) : 0;
                $savedKB   = round(($origSize - $webpSize) / 1024);
                $saved    += max(0, $origSize - $webpSize);

                $this->line(sprintf(
                    '  ✓ %s → %s (%d%% smaller, %dKB saved)',
                    basename($file), basename($webpPath), $pct, $savedKB
                ));
                $converted++;
            }
        }

        $this->info(sprintf(
            '✓ Converted: %d | Skipped: %d | Total saved: %s KB',
            $converted, $skipped, number_format($saved / 1024, 1)
        ));
        return 0;
    }

    private function loadImage(string $path): mixed
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        try {
            return match ($ext) {
                'jpg', 'jpeg' => imagecreatefromjpeg($path),
                'png'         => $this->loadPng($path),
                default       => null,
            };
        } catch (\Throwable) {
            return null;
        }
    }

    private function loadPng(string $path): mixed
    {
        $src = imagecreatefrompng($path);
        if (!$src) return null;

        $w   = imagesx($src);
        $h   = imagesy($src);

        // Always create a fresh truecolor canvas (WebP doesn't support palette images)
        $img = imagecreatetruecolor($w, $h);

        // Preserve alpha / transparency
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefilledrectangle($img, 0, 0, $w, $h, $transparent);
        imagealphablending($img, true);

        imagecopy($img, $src, 0, 0, 0, 0, $w, $h);
        imagedestroy($src);

        return $img;
    }
}
