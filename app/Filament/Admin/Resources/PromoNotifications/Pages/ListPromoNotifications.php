<?php

namespace App\Filament\Admin\Resources\PromoNotifications\Pages;

use App\Filament\Admin\Resources\PromoNotifications\PromoNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromoNotifications extends ListRecords
{
    protected static string $resource = PromoNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('admin.promo_notification.create')),
        ];
    }
}
