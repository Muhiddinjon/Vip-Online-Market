<?php
namespace App\Filament\Admin\Resources\Restaurants\Pages;

use App\Filament\Admin\Resources\Restaurants\RestaurantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
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
                'description' => $data['description'] ?? null, // JSON: {uz,en,tr}
                'address'     => $data['address'] ?? null,
                'lat'         => $data['lat'] ?? null,
                'lng'         => $data['lng'] ?? null,
                'logo'        => $data['logo'] ?? null,
                'cover_image' => $data['cover_image'] ?? null,
                'phone'       => $data['phone'] ?? null,
                'status'      => $data['status'] ?? 'active',
            ]);
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
