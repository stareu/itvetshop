<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: int implements HasLabel
{
    case PENDING = 1;
    case DONE = 2;
    case CANCELED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'В обработке',
            self::DONE => 'Завершён',
            self::CANCELED => 'Отменён'
        };
    }
}
