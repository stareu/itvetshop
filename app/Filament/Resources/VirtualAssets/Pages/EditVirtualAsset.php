<?php

namespace App\Filament\Resources\VirtualAssets\Pages;

use App\Filament\Resources\VirtualAssets\VirtualAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVirtualAsset extends EditRecord
{
    protected static string $resource = VirtualAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
