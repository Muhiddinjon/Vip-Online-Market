<?php
namespace App\Filament\Restaurant\Resources\ProductResource\Pages;

use App\Filament\Restaurant\Resources\ProductResource;
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
                ->mutateFormDataUsing(function (array $data): array {
                    $data['restaurant_id'] = auth()->user()?->restaurant?->id;
                    return $data;
                })
                ->after(function (Product $record, array $data): void {
                    $path = $data['image'] ?? null;
                    if ($path) {
                        $record->images()->create(['path' => $path, 'sort_order' => 0]);
                    }
                }),
        ];
    }
}
