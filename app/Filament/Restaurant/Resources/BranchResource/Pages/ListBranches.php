<?php
namespace App\Filament\Restaurant\Resources\BranchResource\Pages;

use App\Filament\Restaurant\Resources\BranchResource;
use App\Models\Branch;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('admin.branch.create'))
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['restaurant_id'] = auth()->user()?->restaurant?->id;
                    return $data;
                }),
        ];
    }
}
