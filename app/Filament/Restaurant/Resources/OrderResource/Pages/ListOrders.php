<?php
namespace App\Filament\Restaurant\Resources\OrderResource\Pages;

use App\Filament\Restaurant\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected int | string | array $columnSearchDebounce = 500;

    public static function getPollingInterval(): ?string
    {
        return '10s';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
