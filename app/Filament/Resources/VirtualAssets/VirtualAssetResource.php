<?php

namespace App\Filament\Resources\VirtualAssets;

use App\Filament\Resources\VirtualAssets\Pages\CreateVirtualAsset;
use App\Filament\Resources\VirtualAssets\Pages\EditVirtualAsset;
use App\Filament\Resources\VirtualAssets\Pages\ListVirtualAssets;
use App\Filament\Resources\VirtualAssets\Pages\ViewVirtualAsset;
use App\Filament\Resources\VirtualAssets\Schemas\VirtualAssetForm;
use App\Filament\Resources\VirtualAssets\Schemas\VirtualAssetInfolist;
use App\Filament\Resources\VirtualAssets\Tables\VirtualAssetsTable;
use App\Models\VirtualAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VirtualAssetResource extends Resource
{
    protected static ?string $model = VirtualAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

	protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return VirtualAssetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VirtualAssetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VirtualAssetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVirtualAssets::route('/'),
            'create' => CreateVirtualAsset::route('/create'),
            'view' => ViewVirtualAsset::route('/{record}'),
            'edit' => EditVirtualAsset::route('/{record}/edit'),
        ];
    }

	public static function getLabel(): ?string
	{
		return __('common.virtual_asset');
	}

	public static function getPluralModelLabel(): string
	{
		return __('common.virtual_assets');
	}
}
