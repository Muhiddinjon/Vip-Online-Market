<?php
namespace App\Filament\Admin\Resources\ShopCategories\Pages;

use App\Filament\Admin\Resources\ShopCategories\ShopCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShopCategories extends ListRecords
{
    protected static string $resource = ShopCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('admin.shop_category.create')),
        ];
    }
}
