<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                TextInput::make('product_id')
					->label('ID товара')
                    ->numeric(),
                TextInput::make('product_name')
					->label('Название товара')
                    ->required(),
                TextInput::make('product_price')
					->label('Цена товара')
                    ->required()
                    ->numeric()
                    ->postfix('руб.'),
                TextInput::make('quantity')
					->label('Количество')
                    ->required()
                    ->numeric(),
            ]);
    }
}
