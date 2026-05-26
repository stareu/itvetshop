<?php

namespace App\Filament\Resources\VirtualAssets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VirtualAssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('value'),
                TextEntry::make('status'),
                TextEntry::make('product.name')
                    ->label('Product'),
                TextEntry::make('orderItem.id')
                    ->label('Order item')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
