<?php

namespace App\Filament\Admin\Resources\Couriers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CourierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('vehicle_type')
                    ->options(['bike' => 'Bike', 'car' => 'Car', 'scooter' => 'Scooter', 'other' => 'Other'])
                    ->default('bike')
                    ->required(),
                TextInput::make('plate_number'),
                TextInput::make('avatar'),
                Select::make('status')
                    ->options(['available' => 'Available', 'busy' => 'Busy', 'offline' => 'Offline'])
                    ->default('offline')
                    ->required(),
                TextInput::make('current_lat')
                    ->numeric(),
                TextInput::make('current_lng')
                    ->numeric(),
            ]);
    }
}
