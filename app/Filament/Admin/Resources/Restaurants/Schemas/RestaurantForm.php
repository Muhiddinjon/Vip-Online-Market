<?php

namespace App\Filament\Admin\Resources\Restaurants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RestaurantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('address'),
                TextInput::make('lat')
                    ->numeric(),
                TextInput::make('lng')
                    ->numeric(),
                TextInput::make('logo'),
                FileUpload::make('cover_image')
                    ->image(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('working_hours'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
