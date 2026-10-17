<?php

namespace App\Services;

/**
 * Generates a square PWA home-screen icon from the site's logo.
 *
 * The uploaded logo is a wide navbar banner (e.g. 1000x381px), not square.
 * Android/iOS home-screen icons must be square — feeding them a banner
 * directly produces a squished or near-blank icon once the OS applies its
 * crop/mask, which is why installed shortcuts looked broken. This pads the
 * logo onto a square canvas instead, keeping it intact and centered.
 */
class PwaIconGenerator
{
    private const SIZE = 512;

    /**
     * Returns the filesystem path to a cached square icon built from
     * $sourcePath, generating it first if missing or stale. Null if the
     * source doesn't exist or isn't a readable image.
     */
    public static function squareIconPath(string $sourcePath, string $backgroundHex): ?string
    {
        if (!is_file($sourcePath)) {
            return null;
        }

        $cachePath = dirname($sourcePath) . '/pwa-icon-' . md5($sourcePath) . '.png';

        if (is_file($cachePath) && filemtime($cachePath) >= filemtime($sourcePath)) {
            return $cachePath;
        }

        $info = @getimagesize($sourcePath);
        if (!$info) {
            return null;
        }

        [$width, $height, $type] = $info;

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            default        => null,
        };

        if (!$source) {
            return null;
        }

        $canvas = imagecreatetruecolor(self::SIZE, self::SIZE);
        [$r, $g, $b] = self::hexToRgb($backgroundHex);
        $bg = imagecolorallocate($canvas, $r, $g, $b);
        imagefill($canvas, 0, 0, $bg);

        // Fit the logo within ~70% of the canvas and center it, leaving a
        // safe-zone margin so adaptive/masked launcher icons (which crop a
        // circle or squircle out of the square) don't clip the logo itself.
        $maxDim = (int) (self::SIZE * 0.7);
        $scale  = min($maxDim / $width, $maxDim / $height, 1);
        $destW  = max(1, (int) round($width * $scale));
        $destH  = max(1, (int) round($height * $scale));
        $destX  = (int) round((self::SIZE - $destW) / 2);
        $destY  = (int) round((self::SIZE - $destH) / 2);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($source, true);
            imagesavealpha($source, true);
        }

        imagecopyresampled($canvas, $source, $destX, $destY, 0, 0, $destW, $destH, $width, $height);
        imagedestroy($source);

        imagepng($canvas, $cachePath, 6);
        imagedestroy($canvas);

        return $cachePath;
    }

    /** @return array{0:int,1:int,2:int} */
    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
