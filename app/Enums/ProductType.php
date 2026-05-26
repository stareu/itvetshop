<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum ProductType: int implements HasLabel
{
    case PHYSICAL = 1;
    case VIRTUAL = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::PHYSICAL => 'Физический',
            self::VIRTUAL => 'Виртуальный',
        };
    }
}
