<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public int $pendingOrderCount = 0;

    public static function getPollingInterval(): ?string
    {
        return '10s';
    }

    public function mount(): void
    {
        parent::mount();
        $this->pendingOrderCount = Order::where('status', 'pending')->count();
    }

    public function hydrate(): void
    {
        $newCount = Order::where('status', 'pending')->count();

        if ($newCount > $this->pendingOrderCount) {
            $this->dispatch('new-pending-order');
        }

        $this->pendingOrderCount = $newCount;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
