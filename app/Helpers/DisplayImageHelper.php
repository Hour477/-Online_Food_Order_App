<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class DisplayImageHelper
{
    /**
     * Get full image URL or placeholder
     */
    public static function get($image = null, $default = 'assets/img/placeholder.png')
    {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return asset($default);
    }

    /**
     * Get image or custom default (for avatar, product, etc.)
     */
    public static function getWithDefault($image = null, $default = null)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return $default ? asset($default) : asset('assets/img/placeholder.png');
    }

    /**
     * Check if image exists
     */
    public static function exists($image = null)
    {
        return $image && Storage::disk('public')->exists($image);
    }

    /**
     * Get image URL or null (no fallback)
     */
    public static function nullable($image = null)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return null;
    }

    /**
     * Get HTML <img> tag directly
     */
    public static function img($image = null, $class = '', $alt = 'image')
    {
        $src = self::get($image);

        return "<img src='{$src}' class='{$class}' alt='{$alt}'>";
    }
}