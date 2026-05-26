<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Number;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
					->label('Юзер')
                    ->searchable(),
                TextColumn::make('customer_id')
					->label('ID покупателя')
					->searchable(),
                TextColumn::make('status')
					->label('Статус')
                    ->sortable(),
                TextColumn::make('payment_id')
					->label('ID платежа')
                    ->searchable(),
				TextColumn::make('total')
					->label('Сумма')
					->getStateUsing(fn (Order $order) => 
						$order->getTotalAmount()
					),
                TextColumn::make('payment_data')
					->label('Данные платежа')
					->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('payment_system')
					->label('Платёжная система')
                    ->searchable(),
                TextColumn::make('created_at')
					->label('Создан')
                    ->date('d.m.Y h:i:s')
                    ->sortable(),
                TextColumn::make('updated_at')
					->label('Обновлён')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
			->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
