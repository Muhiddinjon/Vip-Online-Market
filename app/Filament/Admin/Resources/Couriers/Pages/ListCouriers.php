<?php

namespace App\Filament\Admin\Resources\Couriers\Pages;

use App\Filament\Admin\Resources\Couriers\CourierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCouriers extends ListRecords
{
    protected static string $resource = CourierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
