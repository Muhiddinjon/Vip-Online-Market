<?php
namespace App\Filament\Restaurant\Resources\ProductResource\Pages;

use App\Filament\Restaurant\Resources\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Mahsulot qo\'shish')];
    }
}
