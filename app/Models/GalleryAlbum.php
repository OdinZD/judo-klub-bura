<?php

namespace App\Models;

use App\Enums\GalleryCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'category',
        'cover_image_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'category' => GalleryCategory::class,
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title);

                $originalSlug = $album->slug;
                $counter = 1;
                while (static::where('slug', $album->slug)->exists()) {
                    $album->slug = $originalSlug.'-'.$counter++;
                }
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory(Builder $query, GalleryCategory $category): Builder
    {
        return $query->where('category', $category);
    }

    protected function coverUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->cover_image_path) {
                return asset('storage/'.$this->cover_image_path);
            }

            $firstImage = $this->images()->first();

            return $firstImage?->thumbnail_url;
        });
    }
}
