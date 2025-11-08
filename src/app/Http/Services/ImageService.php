<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageService
{
    private const IMAGE_QUALITY = 80;
    private const STORAGE_DIRECTORY = 'user_images';

    public function __construct(
        private readonly ImageManager $imageManager
    ) {}

    /**
     * Process image and attach to user
     */
    public function processAndAttachImage(User $user, UploadedFile $image): void
    {
        $filename = $this->generateUniqueFilename();
        $path = $this->processAndSaveImage($image, $filename);

        $user->user_images()->create([
            'image' => $path,
            'original_name' => $image->getClientOriginalName(),
            'size' => $image->getSize(),
        ]);
    }

    /**
     * Process image to WebP format and save
     */
    private function processAndSaveImage(UploadedFile $image, string $filename): string
    {
        $processedImage = $this->imageManager
            ->read($image->getPathname())
            ->toWebp(self::IMAGE_QUALITY);

        $path = self::STORAGE_DIRECTORY . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        $this->ensureDirectoryExists(dirname($fullPath));

        $processedImage->save($fullPath);

        return $path;
    }

    /**
     * Generate unique filename for image
     */
    private function generateUniqueFilename(): string
    {
        return uniqid('img_', true) . '_' . time() . '.webp';
    }

    /**
     * Ensure directory exists
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Delete image file
     */
    public function deleteImage(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }
}