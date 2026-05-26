<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Enums\ProductType;
use Filament\Schemas\Components\Utilities\Get;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
					->label('Название')
                    ->required(),
                Toggle::make('is_active')
					->label('Активный')
                    ->required(),
                Select::make('type')
					->label('Тип')
					->options(ProductType::class)
                    ->required()
					->live(),
                TextInput::make('price')
					->label('Цена')
                    ->required()
                    ->numeric()
					->postfix('руб.'),
				TextInput::make('quantity')
					->label('Количество')
					->numeric()
					->visible(function(Get $get) {
						return $get('type') === ProductType::PHYSICAL;
					}),
                TextInput::make('description')
					->label('Описание'),
                FileUpload::make('image')
					->label('Картинка')
                    ->image()
					->preserveFilenames()
					->disk('public'),
            ]);
    }
}
