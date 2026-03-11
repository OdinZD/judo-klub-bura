<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'date',
        'location',
        'description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $competition) {
            if (empty($competition->slug)) {
                $competition->slug = Str::slug($competition->name);

                $originalSlug = $competition->slug;
                $counter = 1;
                while (static::where('slug', $competition->slug)->exists()) {
                    $competition->slug = $originalSlug.'-'.$counter++;
                }
            }
        });
    }

    public function results(): HasMany
    {
        return $this->hasMany(CompetitionResult::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
