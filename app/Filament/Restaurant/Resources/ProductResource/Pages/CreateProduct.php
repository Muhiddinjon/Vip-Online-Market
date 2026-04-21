<?php
namespace App\Filament\Restaurant\Resources\ProductResource\Pages;

use App\Filament\Restaurant\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['restaurant_id'] = auth()->user()?->restaurant?->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $path = $this->data['image'] ?? null;
        if ($path) {
            $this->record->images()->create(['path' => $path, 'sort_order' => 0]);
        }
    }
}
