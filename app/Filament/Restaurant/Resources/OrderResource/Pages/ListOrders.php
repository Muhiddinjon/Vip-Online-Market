<?php

namespace App\Filament\Restaurant\Resources\OrderResource\Pages;

use App\Filament\Restaurant\Resources\OrderResource;
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
        $this->pendingOrderCount = $this->fetchPendingCount();
    }

    public function hydrate(): void
    {
        $newCount = $this->fetchPendingCount();

        if ($newCount > $this->pendingOrderCount) {
            $this->dispatch('new-pending-order');
        }

        $this->pendingOrderCount = $newCount;
    }

    private function fetchPendingCount(): int
    {
        return Order::where('status', 'pending')
            ->where('restaurant_id', auth()->user()?->restaurant?->id)
            ->count();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
