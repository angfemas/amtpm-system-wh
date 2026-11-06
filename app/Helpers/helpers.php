<?php

if (!function_exists('homepage_asset')) {
    /**
     * Get the correct asset path for homepage images
     *
     * @param string|null $path
     * @return string|null
     */
    function homepage_asset(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Remove 'storage/' prefix if present to get relative path
        $relative = ltrim($path, '/');
        $relative = str_replace('storage/', '', $relative);

        // Use Storage::url() to generate correct URL
        return \Illuminate\Support\Facades\Storage::disk('public')->url($relative);
    }
}
