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
                    foreach ($data['images'] ?? [] as $i => $path) {
                        if ($path) $record->images()->create(['path' => $path, 'sort_order' => $i]);
                    }
                    static::syncBranches($record, $data);
                    static::syncVariants($record, $data);
                }),
        ];
    }

    public static function syncVariants(Product $record, array $data): void
    {
        $record->variants()->delete();
        $imageVisible = (bool) ($data['variants_image_visible'] ?? false);
        foreach ($data['variants'] ?? [] as $i => $row) {
            if (empty($row['name']['uz'] ?? null) || !isset($row['price'])) continue;
            $record->variants()->create([
                'name'          => $row['name'],
                'price'         => $row['price'],
                'sort_order'    => $row['sort_order'] ?? $i,
                'image_visible' => $imageVisible,
            ]);
        }
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
