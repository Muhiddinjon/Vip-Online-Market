<?php
namespace App\Filament\Admin\Widgets;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Restaurant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders    = Order::whereDate('created_at', today())->count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $restaurants    = Restaurant::where('status', 'active')->count();
        $couriers       = Courier::where('status', 'available')->count();
        $monthRevenue   = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        return [
            Stat::make('Bugungi buyurtmalar', $todayOrders)
                ->description('Jami bugun')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Kutilayotgan', $pendingOrders)
                ->description('Hozir pending')
                ->icon('heroicon-o-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Faol restoranlar', $restaurants)
                ->description('Ishlamoqda')
                ->icon('heroicon-o-building-storefront')
                ->color('success'),

            Stat::make('Mavjud kuryerlar', $couriers)
                ->description('Online va bo\'sh')
                ->icon('heroicon-o-truck')
                ->color('info'),

            Stat::make('Oylik daromad', number_format($monthRevenue, 0, '.', ' ') . ' so\'m')
                ->description(now()->format('F Y'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
