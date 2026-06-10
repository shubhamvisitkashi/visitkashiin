<?php

namespace App\Services;

/**
 * GD-based image compressor. Resizes oversized images and re-encodes them
 * to fit under a target file size while keeping visual quality acceptable
 * (never drops below a minimum quality / minimum dimension floor).
 */
class ImageCompressor
{
    /** Longest-edge cap in pixels before any quality reduction is applied. */
    private const MAX_DIMENSION = 1920;

    /** Never let the longest edge go below this when shrinking further. */
    private const MIN_DIMENSION = 1024;

    /** Never encode JPEG/WebP below this quality (1-100). */
    private const MIN_QUALITY = 60;

    /**
     * Compress an image file in place. Safe no-op if the file is missing,
     * not a supported image, or already under the target size.
     *
     * @return bool true if the file was rewritten (and is smaller than before)
     */
    public static function compress(string $path, int $maxBytes = 100 * 1024): bool
    {
        if (!is_file($path)) {
            return false;
        }

        $originalSize = filesize($path);
        if ($originalSize <= $maxBytes) {
            return false;
        }

        $info = @getimagesize($path);
        if (!$info) {
            return false;
        }

        [$width, $height, $type] = $info;

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default        => null,
        };

        if (!$image) {
            return false;
        }

        if ($type === IMAGETYPE_PNG) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        // Cap the longest edge before doing anything else.
        $longest = max($width, $height);
        if ($longest > self::MAX_DIMENSION) {
            $factor = self::MAX_DIMENSION / $longest;
            $width  = (int) round($width * $factor);
            $height = (int) round($height * $factor);
            $image  = self::resize($image, $width, $height, $type);
        }

        $tmp     = $path . '.compress.tmp';
        $quality = $type === IMAGETYPE_PNG ? 9 : 85;

        for ($i = 0; $i < 20; $i++) {
            self::save($image, $tmp, $type, $quality);
            clearstatcache(true, $tmp);
            $newSize = filesize($tmp);

            if ($newSize <= $maxBytes) {
                break;
            }

            $canShrink = max($width, $height) > self::MIN_DIMENSION;

            if ($type === IMAGETYPE_PNG) {
                // PNG quality (0-9) is lossless compression effort, not visual
                // quality — once at 9, the only lever left is dimensions.
                if (!$canShrink) {
                    break;
                }
                [$width, $height, $image] = self::shrink($image, $width, $height, $type);
            } else {
                if ($quality > self::MIN_QUALITY) {
                    $quality = max(self::MIN_QUALITY, $quality - 5);
                } elseif ($canShrink) {
                    [$width, $height, $image] = self::shrink($image, $width, $height, $type);
                    $quality = 80;
                } else {
                    break;
                }
            }
        }

        imagedestroy($image);

        clearstatcache(true, $tmp);
        if (is_file($tmp) && filesize($tmp) > 0 && filesize($tmp) < $originalSize) {
            rename($tmp, $path);
            return true;
        }

        if (is_file($tmp)) {
            @unlink($tmp);
        }

        return false;
    }

    /**
     * Reduce dimensions by ~15% (preserving aspect ratio), without letting
     * the longest edge drop below MIN_DIMENSION.
     *
     * @return array{0: int, 1: int, 2: resource|\GdImage}
     */
    private static function shrink($image, int $width, int $height, int $type): array
    {
        $longest    = max($width, $height);
        $newLongest = max(self::MIN_DIMENSION, (int) round($longest * 0.85));
        $factor     = $newLongest / $longest;

        $width  = max(1, (int) round($width * $factor));
        $height = max(1, (int) round($height * $factor));

        return [$width, $height, self::resize($image, $width, $height, $type)];
    }

    /**
     * @return resource|\GdImage
     */
    private static function resize($image, int $width, int $height, int $type)
    {
        $new = imagecreatetruecolor($width, $height);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($new, false);
            imagesavealpha($new, true);
            $transparent = imagecolorallocatealpha($new, 0, 0, 0, 127);
            imagefilledrectangle($new, 0, 0, $width, $height, $transparent);
        }

        imagecopyresampled($new, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        imagedestroy($image);

        return $new;
    }

    /**
     * @param resource|\GdImage $image
     */
    private static function save($image, string $path, int $type, int $quality): void
    {
        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($image, $path, $quality),
            IMAGETYPE_PNG  => imagepng($image, $path, $quality),
            IMAGETYPE_WEBP => imagewebp($image, $path, $quality),
            default        => null,
        };
    }
}
