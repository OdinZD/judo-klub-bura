<?php

namespace App\Enums;

enum PlacementType: string
{
    case Gold = 'gold';
    case Silver = 'silver';
    case Bronze = 'bronze';
    case Fifth = 'fifth';
    case Seventh = 'seventh';
    case Participation = 'participation';

    public function label(): string
    {
        return match ($this) {
            self::Gold => 'Zlato',
            self::Silver => 'Srebro',
            self::Bronze => 'Bronca',
            self::Fifth => '5. mjesto',
            self::Seventh => '7. mjesto',
            self::Participation => 'Sudjelovanje',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Gold => 'amber',
            self::Silver => 'zinc',
            self::Bronze => 'orange',
            self::Fifth => 'slate',
            self::Seventh => 'slate',
            self::Participation => 'sky',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Gold, self::Silver, self::Bronze => 'trophy',
            default => 'star',
        };
    }
}
