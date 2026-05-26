<?php

namespace App\Filament\Resources\VirtualAssets\Pages;

use App\Filament\Resources\VirtualAssets\VirtualAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVirtualAssets extends ListRecords
{
    protected static string $resource = VirtualAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
