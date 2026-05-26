<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('product_id')
					->label('ID товара')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product_name')
					->label('Название товара')
                    ->searchable(),
                TextColumn::make('product_price')
					->label('Цена товара')
                    ->sortable(),
                TextColumn::make('quantity')
					->label('Количество')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
					->label('Создан')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
					->label('Обновлён')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
	
	public static function getTitle(Model $ownerRecord, string $pageClass): string
	{
		return __('common.order_items');
	}
}
