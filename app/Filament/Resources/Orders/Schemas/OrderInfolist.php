<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
					->label('Юзер'),
                TextEntry::make('customer.email')
					->label('Email покупателя'),
                TextEntry::make('status')
					->label('Статус'),
                TextEntry::make('payment_id')
					->label('ID платежа'),
                TextEntry::make('payment_data')
					->label('Данные платежа'),
                TextEntry::make('payment_system')
					->label('Платёжная система'),
                TextEntry::make('created_at')
					->label('Создан')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
					->label('Обновлён')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
