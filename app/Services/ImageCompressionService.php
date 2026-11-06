<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressionService
{
    protected ImageManager $manager;

    protected int $maxWidth = 1920;

    protected int $maxHeight = 1080;

    protected int $quality = 80;

    protected bool $convertToWebP = false;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());

        // Load config if exists
        if (config()->has('image-compression')) {
            $this->maxWidth = config('image-compression.max_width', 1920);
            $this->maxHeight = config('image-compression.max_height', 1080);
            $this->quality = config('image-compression.quality', 80);
            $this->convertToWebP = config('image-compression.convert_to_webp', false);
        }
    }

    /**
     * Compress and optimize uploaded image
     */
    public function compressAndStore(UploadedFile $file, string $path, string $disk = 'public'): string
    {
        $image = $this->manager->read($file);

        // Resize if image is too large
        $image = $this->resizeIfNeeded($image);

        // Get original extension
        $extension = $file->getClientOriginalExtension();

        // Convert to WebP if enabled
        if ($this->convertToWebP && $extension !== 'webp') {
            $extension = 'webp';
        }

        // Generate filename
        $filename = $this->generateFilename($file, $extension);
        $fullPath = $path . '/' . $filename;

        // Encode and store
        $encoded = $image->toJpeg($this->quality);

        if ($this->convertToWebP && $extension === 'webp') {
            $encoded = $image->toWebp($this->quality);
        }

        Storage::disk($disk)->put($fullPath, $encoded);

        return $fullPath;
    }

    /**
     * Compress existing image from path
     */
    public function compressExistingImage(string $sourcePath, string $destinationPath, string $disk = 'public'): string
    {
        $image = $this->manager->read(Storage::disk($disk)->get($sourcePath));

        // Resize if needed
        $image = $this->resizeIfNeeded($image);

        // Encode with quality
        $encoded = $image->toJpeg($this->quality);

        Storage::disk($disk)->put($destinationPath, $encoded);

        return $destinationPath;
    }

    /**
     * Resize image if it exceeds max dimensions
     */
    protected function resizeIfNeeded($image)
    {
        $width = $image->width();
        $height = $image->height();

        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        return $image;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file, string $extension): string
    {
        return time() . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Set custom quality
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(10, min(100, $quality));
        return $this;
    }

    /**
     * Set custom max dimensions
     */
    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }

    /**
     * Enable or disable WebP conversion
     */
    public function setConvertToWebP(bool $enabled): self
    {
        $this->convertToWebP = $enabled;
        return $this;
    }

    /**
     * Get image info before compression
     */
    public function getImageInfo(UploadedFile $file): array
    {
        $image = $this->manager->read($file);

        return [
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => $file->getSize(),
            'size_human' => $this->formatBytes($file->getSize()),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
