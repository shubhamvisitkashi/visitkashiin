<?php

namespace App\Console\Commands;

use App\Services\ImageCompressor;
use Illuminate\Console\Command;

class CompressImages extends Command
{
    protected $signature = 'images:compress
        {--dir= : Single directory (relative to public/) to process}
        {--max-kb=100 : Target max size in KB}
        {--dry-run : List files that would be compressed without changing them}
        {--limit=0 : Stop after N files (0 = no limit)}';

    protected $description = 'Compress existing JPG/PNG/WebP images in place to reduce file size without visible quality loss';

    private array $dirs = [
        'backend/admin/product_images',
        'backend/admin/hero_slides',
        'backend/admin/website_setup',
        'backend/admin/promo_banners',
        'backend/admin/package_images',
        'uploads/avatars',
    ];

    public function handle(): int
    {
        $maxBytes = (int) $this->option('max-kb') * 1024;
        $dryRun   = (bool) $this->option('dry-run');
        $limit    = (int) $this->option('limit');
        $dirOpt   = $this->option('dir');

        $dirs = $dirOpt ? [trim($dirOpt, '/')] : $this->dirs;

        $processed = 0;
        $compressed = 0;
        $savedBytes = 0;
        $failed = 0;

        foreach ($dirs as $relDir) {
            $fullDir = public_path($relDir);
            if (!is_dir($fullDir)) {
                continue;
            }

            $files = glob($fullDir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
            sort($files);

            foreach ($files as $file) {
                $before = filesize($file);
                if ($before <= $maxBytes) {
                    continue;
                }

                if ($limit > 0 && $processed >= $limit) {
                    break 2;
                }
                $processed++;

                if ($dryRun) {
                    $this->line(sprintf('  %s (%s KB)', $relDir . '/' . basename($file), number_format($before / 1024, 1)));
                    continue;
                }

                $ok = ImageCompressor::compress($file, $maxBytes);
                clearstatcache(true, $file);
                $after = filesize($file);

                if ($ok) {
                    $compressed++;
                    $savedBytes += ($before - $after);
                    $pct = round(($before - $after) / $before * 100);
                    $this->line(sprintf(
                        '  ✓ %-55s %7.1f KB → %7.1f KB (-%d%%)',
                        $relDir . '/' . basename($file), $before / 1024, $after / 1024, $pct
                    ));
                } else {
                    $failed++;
                    $this->line(sprintf('  ⚠ %-55s skipped (unsupported or no improvement)', $relDir . '/' . basename($file)));
                }
            }
        }

        if ($dryRun) {
            $this->info(sprintf('Found %d file(s) above %dKB.', $processed, $maxBytes / 1024));
            return 0;
        }

        $this->info(sprintf(
            'Processed: %d | Compressed: %d | Skipped/failed: %d | Total saved: %s MB',
            $processed, $compressed, $failed, number_format($savedBytes / 1048576, 2)
        ));

        return 0;
    }
}
