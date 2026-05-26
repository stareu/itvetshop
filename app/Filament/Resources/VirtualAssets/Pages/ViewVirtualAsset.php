<?php

namespace App\Filament\Resources\VirtualAssets\Pages;

use App\Filament\Resources\VirtualAssets\VirtualAssetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVirtualAsset extends ViewRecord
{
    protected static string $resource = VirtualAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
