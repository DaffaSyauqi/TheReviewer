<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    private const STORAGE_DISK = 's3';

    public function __construct() {}

    public function upload(UploadedFile $file, int $placeId): array
    {
        $imageData = $this->processImage($file);

        $path = $this->storeImage($imageData, $placeId);

        return [
            'disk' => self::STORAGE_DISK,
            'path' => $path,
            'mime_type' => 'image/webp',
            'size' => strlen($imageData),
        ];
    }

    private function processImage(UploadedFile $file): string
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->read($file->getRealPath());

        return (string) $image
            ->scaleDown(width: 2000)
            ->toWebp(quality: 80);
    }

    private function storeImage(string $imageData, int $placeId): string
    {
        $timestamp = now()->timestamp;
        $randomString = Str::random(8);
        $filename = "{$timestamp}-{$randomString}.webp";
        $path = "place-images/{$placeId}/{$filename}";

        Storage::disk(self::STORAGE_DISK)->put($path, $imageData);

        return $path;
    }
}
