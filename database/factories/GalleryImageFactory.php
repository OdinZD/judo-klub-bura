<?php

namespace Database\Factories;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    protected $model = GalleryImage::class;

    public function definition(): array
    {
        return [
            'gallery_album_id' => GalleryAlbum::factory(),
            'image_path' => 'gallery/originals/1/'.fake()->uuid().'.jpg',
            'thumbnail_path' => 'gallery/thumbnails/1/'.fake()->uuid().'.jpg',
            'caption' => fake()->optional()->sentence(),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
