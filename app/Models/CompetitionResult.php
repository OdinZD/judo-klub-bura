<?php

namespace App\Models;

use App\Enums\PlacementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionResult extends Model
{
    protected $fillable = [
        'competition_id',
        'athlete_name',
        'weight_category',
        'placement',
    ];

    protected function casts(): array
    {
        return [
            'placement' => PlacementType::class,
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
