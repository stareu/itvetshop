<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('product_id')
					->label('ID товара')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('product_name')
					->label('Название товара'),
                TextEntry::make('product_price')
					->label('Цена товара')
                    ->money(),
                TextEntry::make('quantity')
					->label('Количество')
                    ->numeric(),
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
