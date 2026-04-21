<?php

namespace App\Filament\Admin\Resources\Restaurants\Pages;

use App\Filament\Admin\Resources\Restaurants\RestaurantResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListRestaurants extends ListRecords
{
    protected static string $resource = RestaurantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('admin.restaurant.create'))
                ->createAnother(false)
                ->using(function (array $data): Model {
                    return DB::transaction(function () use ($data) {
                        $user = User::create([
                            'name'     => $data['name'],
                            'email'    => $data['email'],
                            'password' => bcrypt($data['password']),
                            'role'     => 'restaurant',
                            'status'   => 'active',
                        ]);

                        return $user->restaurant()->create([
                            'name'        => $data['name'],
                            'description' => $data['description'] ?? null,
                            'address'     => $data['address'] ?? null,
                            'lat'         => $data['lat'] ?? null,
                            'lng'         => $data['lng'] ?? null,
                            'logo'        => $data['logo'] ?? null,
                            'cover_image' => $data['cover_image'] ?? null,
                            'phone'       => $data['phone'] ?? null,
                            'status'      => $data['status'] ?? 'active',
                        ]);
                    });
                }),
        ];
    }
}
