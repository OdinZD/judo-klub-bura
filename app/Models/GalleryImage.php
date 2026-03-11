<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_album_id',
        'image_path',
        'thumbnail_path',
        'caption',
        'sort_order',
    ];

    protected $appends = ['image_url', 'thumbnail_url'];

    protected static function booted(): void
    {
        static::deleting(function (self $image) {
            Storage::disk('public')->delete([
                $image->image_path,
                $image->thumbnail_path,
            ]);
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->image_path));
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->thumbnail_path));
    }
}
