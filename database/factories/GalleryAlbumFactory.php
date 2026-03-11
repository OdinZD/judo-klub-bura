<?php

namespace Database\Factories;

use App\Enums\GalleryCategory;
use App\Models\GalleryAlbum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryAlbum>
 */
class GalleryAlbumFactory extends Factory
{
    protected $model = GalleryAlbum::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'event_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'category' => fake()->randomElement(GalleryCategory::cases()),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
