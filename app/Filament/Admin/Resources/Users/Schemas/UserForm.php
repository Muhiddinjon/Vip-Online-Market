<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options([
            'admin' => 'Admin',
            'moderator' => 'Moderator',
            'restaurant' => 'Restaurant',
            'courier' => 'Courier',
            'customer' => 'Customer',
        ])
                    ->default('customer')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'blocked' => 'Blocked'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
