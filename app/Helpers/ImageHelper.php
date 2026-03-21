<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Upload image
     */
    public static function upload($file, $folder = 'uploads')
    {
        if (!$file) return null;

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());

        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Update image (delete old + upload new)
     */
    public static function update($file, $oldImage = null, $folder = 'uploads')
    {
        // Upload new image
        $newImage = self::upload($file, $folder);

        // Delete old image
        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }

        return $newImage;
    }

    /**
     * Delete image
     */
    public static function delete($image)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::disk('public')->delete($image);
        }

        return false;
    }

    /**
     * Copy image
     */
    public static function copy($image, $folder = 'uploads')
    {
        if (!$image || !Storage::disk('public')->exists($image)) {
            return null;
        }

        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $newName = time() . '_copy.' . $extension;
        $newPath = $folder . '/' . $newName;

        Storage::disk('public')->copy($image, $newPath);

        return $newPath;
    }

    /**
     * Get full URL
     */
    public static function getFullPath($image = null)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return asset('assets/img/placeholder.jpg');
    }
}