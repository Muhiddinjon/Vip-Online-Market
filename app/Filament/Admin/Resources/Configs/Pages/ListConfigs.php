<?php
namespace App\Filament\Admin\Resources\Configs\Pages;

use App\Filament\Admin\Resources\Configs\ConfigResource;
use Filament\Resources\Pages\ListRecords;

class ListConfigs extends ListRecords
{
    protected static string $resource = ConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
