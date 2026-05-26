<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Enums\OrderStatus;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
					->label('Юзер')
                    ->relationship('user', 'name'),
                TextInput::make('customer_id')
					->label('ID покупателя'),
                Select::make('status')
					->label('Статус')
					->options(OrderStatus::class)
                    ->required(),
                TextInput::make('payment_id')
					->label('ID платежа')
                    ->required(),
                TextInput::make('payment_data')
					->label('Данные платежа')
                    ->required(),
                TextInput::make('payment_system')
					->label('Платёжная система')
                    ->required(),
            ]);
    }
}
