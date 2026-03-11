<?php

namespace App\Enums;

enum DayOfWeek: int
{
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case Sunday = 7;

    public function label(): string
    {
        return match ($this) {
            self::Monday => 'Ponedjeljak',
            self::Tuesday => 'Utorak',
            self::Wednesday => 'Srijeda',
            self::Thursday => 'Četvrtak',
            self::Friday => 'Petak',
            self::Saturday => 'Subota',
            self::Sunday => 'Nedjelja',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Monday => 'Pon',
            self::Tuesday => 'Uto',
            self::Wednesday => 'Sri',
            self::Thursday => 'Čet',
            self::Friday => 'Pet',
            self::Saturday => 'Sub',
            self::Sunday => 'Ned',
        };
    }
}
