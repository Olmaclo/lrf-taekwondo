<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store an uploaded image, resized and compressed, in the given directory.
     * Returns the stored relative path.
     *
     * @param  int  $maxDimension  Max width or height in pixels
     * @param  int  $quality       JPEG/WebP quality (0-100)
     */
    public static function storeOptimized(
        UploadedFile $file,
        string $directory,
        int $maxDimension = 1200,
        int $quality = 80
    ): string {
        $extension = 'jpg';
        $filename  = $directory . '/' . Str::random(40) . '.' . $extension;

        if (! function_exists('imagecreatefromjpeg')) {
            // GD not available — fall back to raw storage
            return $file->store($directory, 'public');
        }

        $mime = $file->getMimeType();
        $src  = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png'               => @imagecreatefrompng($file->getRealPath()),
            'image/webp'              => function_exists('imagecreatefromwebp')
                                            ? @imagecreatefromwebp($file->getRealPath())
                                            : null,
            default                   => null,
        };

        if (! $src) {
            return $file->store($directory, 'public');
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        if ($origW > $maxDimension || $origH > $maxDimension) {
            $scale = min($maxDimension / $origW, $maxDimension / $origH);
            $newW  = (int) round($origW * $scale);
            $newH  = (int) round($origH * $scale);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        ob_start();
        imagejpeg($dst, null, $quality);
        $imageData = ob_get_clean();
        imagedestroy($dst);

        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    /**
     * Recompress an already-stored image file in place.
     * Returns new file size, or null if GD is unavailable.
     */
    public static function recompressInPlace(
        string $fullPath,
        string $mime,
        int $maxDimension = 1200,
        int $quality = 80
    ): ?int {
        if (! function_exists('imagecreatefromjpeg')) {
            return null;
        }

        [$origW, $origH] = @getimagesize($fullPath) ?: [0, 0];
        if ($origW === 0 || $origH === 0) {
            return filesize($fullPath) ?: null;
        }

        $src = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
            'image/png'               => @imagecreatefrompng($fullPath),
            'image/webp'              => function_exists('imagecreatefromwebp')
                                            ? @imagecreatefromwebp($fullPath)
                                            : null,
            default => null,
        };

        if (! $src) {
            return filesize($fullPath) ?: null;
        }

        if ($origW > $maxDimension || $origH > $maxDimension) {
            $scale = min($maxDimension / $origW, $maxDimension / $origH);
            $newW  = (int) round($origW * $scale);
            $newH  = (int) round($origH * $scale);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);
        imagejpeg($dst, $fullPath, $quality);
        imagedestroy($dst);

        return filesize($fullPath) ?: null;
    }
}
