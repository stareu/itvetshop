<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel
{
    case ADMIN = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Админ',
        };
    }
}
