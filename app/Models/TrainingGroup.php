<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingGroup extends Model
{
    protected $fillable = [
        'name',
        'age_range',
        'icon',
        'icon_color',
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

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class)->orderBy('day_of_week');
    }
}
