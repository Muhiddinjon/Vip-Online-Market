<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('restaurant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('courier_id')
                    ->numeric(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'picked_up' => 'Picked up',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('payment_method')
                    ->options(['cash' => 'Cash', 'card' => 'Card'])
                    ->default('cash')
                    ->required(),
                Select::make('payment_status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid'])
                    ->default('pending')
                    ->required(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_address'),
                TextInput::make('delivery_lat')
                    ->numeric(),
                TextInput::make('delivery_lng')
                    ->numeric(),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
