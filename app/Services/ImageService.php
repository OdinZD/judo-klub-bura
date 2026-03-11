<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver);
    }

    public function store(UploadedFile $file, int $albumId): array
    {
        $uuid = Str::uuid();
        $originalPath = "gallery/originals/{$albumId}/{$uuid}.jpg";
        $thumbnailPath = "gallery/thumbnails/{$albumId}/{$uuid}.jpg";

        $image = $this->manager->read($file->getPathname())->orient();

        Storage::disk('public')->put(
            $originalPath,
            $image->encodeByExtension('jpg', quality: 85)->toString()
        );

        $thumbnail = $image->scaleDown(width: 400);

        Storage::disk('public')->put(
            $thumbnailPath,
            $thumbnail->encodeByExtension('jpg', quality: 80)->toString()
        );

        return [
            'image_path' => $originalPath,
            'thumbnail_path' => $thumbnailPath,
        ];
    }

    public function delete(string $imagePath, string $thumbnailPath): void
    {
        Storage::disk('public')->delete([$imagePath, $thumbnailPath]);
    }
}
