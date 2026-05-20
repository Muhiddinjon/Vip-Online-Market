<?php
namespace App\Filament\Restaurant\Resources\ProductResource\Pages;

use App\Filament\Restaurant\Resources\ProductResource;
use App\Models\Branch;
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
                    // Images
                    foreach ($data['images'] ?? [] as $i => $path) {
                        if ($path) $record->images()->create(['path' => $path, 'sort_order' => $i]);
                    }
                    // Branch availability
                    static::syncBranches($record, $data);
                }),
        ];
    }

    public static function syncBranches(Product $record, array $data): void
    {
        $allBranches = Branch::withoutTrashed()
            ->where('restaurant_id', $record->restaurant_id)
            ->pluck('id');

        if ($allBranches->isEmpty()) return;

        $selected = $data['branch_ids'] ?? $allBranches->toArray();

        $syncData = $allBranches->mapWithKeys(
            fn ($id) => [$id => ['is_available' => in_array($id, $selected)]]
        );

        $record->branches()->sync($syncData);
    }
}
