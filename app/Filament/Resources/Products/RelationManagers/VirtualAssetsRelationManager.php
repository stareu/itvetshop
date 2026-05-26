<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ProductType;
use App\Enums\VirtualAssetStatus;
use App\Filament\Resources\Orders\OrderResource;

class VirtualAssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'virtualAssets';

	public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
	{
		return $ownerRecord->type === ProductType::VIRTUAL;
	}

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')
					->label('Значение')
                    ->required(),
                Select::make('status')
					->label('Статус')
					->options(VirtualAssetStatus::class)
					->default(VirtualAssetStatus::FREE)
                    ->required(),
                TextInput::make('comment')
					->columnSpanFull()
					->label('Комментарий'),
                // Select::make('order_item_id')
                //     ->relationship('orderItem', 'id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('value')
					->label('Значение')
                    ->searchable(),
                TextColumn::make('status')
					->label('Статус')
                    ->sortable(),
                // TextColumn::make('orderItem.id')
                //     ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
				Action::make('order')
					->label('Заказ')
					->url(fn ($record) => OrderResource::getUrl('edit', [ 'record' => $record->orderItem->order ]))
					->hidden(function($record) {
						return !isset($record->orderItem);
					})
					->button(),
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
		return __('common.virtual_assets');
	}

	public static function getModelLabel(): ?string
	{
		return __('common.virtual_asset');
	}

	public static function getPluralModelLabel(): string
	{
		return __('common.virtual_assets');
	}
}
