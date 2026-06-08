<?php

namespace App\Filament\Admin\Resources\PromoNotifications\Pages;

use App\Filament\Admin\Resources\PromoNotifications\PromoNotificationResource;
use Filament\Resources\Pages\EditRecord;

class EditPromoNotification extends EditRecord
{
    protected static string $resource = PromoNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
