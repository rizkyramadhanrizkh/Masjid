<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Compress and store uploaded image.
     * Automatically converts to webp or optimized jpg, scales down to max 1200px width.
     */
    public function optimizeAndStore(UploadedFile $file, string $directory = 'uploads'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(24).'.webp';
        $destinationPath = storage_path('app/public/'.$directory);

        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fullTargetPath = $destinationPath.'/'.$filename;
        $tempPath = $file->getRealPath();

        // Check if GD supports webp
        $supportsWebp = function_exists('imagewebp');
        if (! $supportsWebp) {
            $filename = Str::random(24).'.jpg';
            $fullTargetPath = $destinationPath.'/'.$filename;
        }

        try {
            $sourceImage = match ($extension) {
                'jpg', 'jpeg' => @imagecreatefromjpeg($tempPath),
                'png' => @imagecreatefrompng($tempPath),
                'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tempPath) : false,
                'gif' => @imagecreatefromgif($tempPath),
                default => false,
            };

            if (! $sourceImage) {
                // Fallback to standard Laravel storage if GD failed to read image
                $storedPath = $file->store($directory, 'public');

                return 'storage/'.$storedPath;
            }

            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);

            $maxWidth = 1200;
            if ($origWidth > $maxWidth) {
                $targetWidth = $maxWidth;
                $targetHeight = (int) round(($origHeight / $origWidth) * $targetWidth);
            } else {
                $targetWidth = $origWidth;
                $targetHeight = $origHeight;
            }

            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            // Handle transparency
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

            // Resample
            imagecopyresampled($canvas, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

            if ($supportsWebp) {
                imagewebp($canvas, $fullTargetPath, 82);
            } else {
                imagejpeg($canvas, $fullTargetPath, 85);
            }

            imagedestroy($sourceImage);
            imagedestroy($canvas);

            return 'storage/'.$directory.'/'.$filename;
        } catch (\Throwable) {
            $storedPath = $file->store($directory, 'public');

            return 'storage/'.$storedPath;
        }
    }
}
