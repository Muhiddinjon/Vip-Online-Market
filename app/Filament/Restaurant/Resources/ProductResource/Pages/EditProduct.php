<?php
namespace App\Filament\Restaurant\Resources\ProductResource\Pages;

use App\Filament\Restaurant\Resources\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label('O\'chirish')];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['image'] = $this->record->images()->first()?->path;
        return $data;
    }

    protected function afterSave(): void
    {
        $path = $this->data['image'] ?? null;
        $this->record->images()->delete();
        if ($path) {
            $this->record->images()->create(['path' => $path, 'sort_order' => 0]);
        }
    }
}
