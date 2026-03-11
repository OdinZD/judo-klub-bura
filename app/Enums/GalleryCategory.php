<?php

namespace App\Enums;

enum GalleryCategory: string
{
    case Treninzi = 'treninzi';
    case Natjecanja = 'natjecanja';
    case Dogadaji = 'dogadaji';

    public function label(): string
    {
        return match ($this) {
            self::Treninzi => 'Treninzi',
            self::Natjecanja => 'Natjecanja',
            self::Dogadaji => 'Događaji',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Treninzi => 'sky',
            self::Natjecanja => 'amber',
            self::Dogadaji => 'teal',
        };
    }
}
