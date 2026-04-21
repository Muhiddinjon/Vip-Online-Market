<?php
namespace App\Filament\Admin\Resources\Restaurants\Pages;

use App\Filament\Admin\Resources\Restaurants\RestaurantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRestaurant extends EditRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label('O\'chirish')];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['email'] = $this->record->user?->email ?? '';
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // User email va name yangilash
        if (!empty($data['email'])) {
            $record->user?->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);
        }

        $record->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null, // JSON: {uz,en,tr}
            'address'     => $data['address'] ?? null,
            'lat'         => $data['lat'] ?? null,
            'lng'         => $data['lng'] ?? null,
            'logo'        => $data['logo'] ?? $record->logo,
            'cover_image' => $data['cover_image'] ?? $record->cover_image,
            'phone'       => $data['phone'] ?? null,
            'status'      => $data['status'],
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
