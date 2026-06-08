<?php

namespace App\Filament\Admin\Resources\PromoNotifications\Pages;

use App\Filament\Admin\Resources\PromoNotifications\PromoNotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromoNotification extends CreateRecord
{
    protected static string $resource = PromoNotificationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
