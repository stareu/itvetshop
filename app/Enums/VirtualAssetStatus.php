<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum VirtualAssetStatus: int implements HasLabel
{
    case FREE = 1;
    case RESERVED = 2;
    case PAID = 3;

	public function getLabel(): string
    {
        return match ($this) {
            self::FREE => 'Свободен',
            self::RESERVED => 'Зарезервирован',
            self::PAID => 'Оплачен',
        };
    }
}
