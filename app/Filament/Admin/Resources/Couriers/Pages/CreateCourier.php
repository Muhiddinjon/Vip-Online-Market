<?php
namespace App\Filament\Admin\Resources\Couriers\Pages;

use App\Filament\Admin\Resources\Couriers\CourierResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateCourier extends CreateRecord
{
    protected static string $resource = CourierResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'password' => bcrypt($data['password']),
                'role'     => 'courier',
                'status'   => 'active',
            ]);

            return $user->courier()->create([
                'vehicle_type' => $data['vehicle_type'],
                'plate_number' => $data['plate_number'] ?? null,
                'avatar'       => $data['avatar'] ?? null,
                'status'       => $data['status'] ?? 'offline',
            ]);
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
