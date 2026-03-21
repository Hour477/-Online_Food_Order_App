<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadImageHelper
{
    public static function upload(string $dir, string $format, $image = null)
    {

        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            // Handle both file path and uploaded file object
            if (is_object($image) && method_exists($image, 'move')) {
                // It's an uploaded file, use Laravel's store method
                $path = $image->store($dir, ['disk' => 'public']);
                return basename($path);
            } else if (is_string($image) && file_exists($image)) {
                // It's a file path
                Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
            } else {
                // Fallback: treat as temporary file
                Storage::disk('public')->put($dir . $imageName, $image);
            }
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = UploadImageHelper::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }
    public static function copy($copyPath, $toPath)
    {
        if (Storage::disk('public')->exists($copyPath)) {
            Storage::disk('public')->copy($copyPath, $toPath);
        }
        return [
            'success' => 1,
            'message' => 'Copy successfully !'
        ];
    }
}
