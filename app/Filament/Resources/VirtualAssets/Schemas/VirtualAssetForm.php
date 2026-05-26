<?php

namespace App\Filament\Resources\VirtualAssets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Enums\VirtualAssetStatus;

class VirtualAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')
                    ->required(),
				TextInput::make('comment'),
                Select::make('status')
                    ->options(VirtualAssetStatus::class)
					->required(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('order_item_id')
                    ->relationship('orderItem', 'id'),
            ]);
    }
}
