<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Compression Settings
    |--------------------------------------------------------------------------
    |
    | Configure image compression settings for uploaded images.
    | These settings are used by the ImageCompressionService.
    |
    */

    // Maximum width for resized images (in pixels)
    'max_width' => env('IMAGE_MAX_WIDTH', 1920),

    // Maximum height for resized images (in pixels)
    'max_height' => env('IMAGE_MAX_HEIGHT', 1080),

    // JPEG/WebP quality (1-100, higher = better quality but larger file)
    'quality' => env('IMAGE_QUALITY', 80),

    // Convert all images to WebP format (WebP has better compression)
    'convert_to_webp' => env('IMAGE_CONVERT_TO_WEBP', false),

    // Enable progressive JPEG loading
    'progressive_jpeg' => env('IMAGE_PROGRESSIVE_JPEG', true),

    // Optimize PNG images (remove metadata)
    'optimize_png' => env('IMAGE_OPTIMIZE_PNG', true),
];
