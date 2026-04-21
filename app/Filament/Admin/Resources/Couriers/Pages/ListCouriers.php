<?php

namespace App\Filament\Admin\Resources\Couriers\Pages;

use App\Filament\Admin\Resources\Couriers\CourierResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListCouriers extends ListRecords
{
    protected static string $resource = CourierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('admin.courier.create'))
                ->createAnother(false)
                ->using(function (array $data): Model {
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
                }),
        ];
    }
}
