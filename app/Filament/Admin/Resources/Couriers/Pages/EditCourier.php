<?php
namespace App\Filament\Admin\Resources\Couriers\Pages;

use App\Filament\Admin\Resources\Couriers\CourierResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCourier extends EditRecord
{
    protected static string $resource = CourierResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->label('O\'chirish')];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['name']  = $this->record->user?->name ?? '';
        $data['phone'] = $this->record->user?->phone ?? '';
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->user?->update([
            'name'  => $data['name'] ?? $record->user->name,
            'phone' => $data['phone'] ?? $record->user->phone,
        ]);

        $record->update([
            'vehicle_type' => $data['vehicle_type'],
            'plate_number' => $data['plate_number'] ?? null,
            'avatar'       => $data['avatar'] ?? $record->avatar,
            'status'       => $data['status'] ?? $record->status,
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
