<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Coach extends Model
{
    protected $fillable = [
        'name',
        'role',
        'belt',
        'bio',
        'photo_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $coach) {
            if ($coach->photo_path) {
                Storage::disk('public')->delete($coach->photo_path);
            }
        });
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo_path ? asset('storage/'.$this->photo_path) : null);
    }
}
