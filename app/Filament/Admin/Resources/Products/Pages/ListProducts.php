<?php
namespace App\Filament\Admin\Resources\Products\Pages;

use App\Filament\Admin\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('admin.product.create'))
                ->createAnother(false)
                ->after(function (Product $record, array $data): void {
                    $paths = array_values(array_filter($data['images'] ?? []));
                    foreach ($paths as $i => $path) {
                        $record->images()->create(['path' => $path, 'sort_order' => $i]);
                    }
                }),
        ];
    }
}
