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
            Stat::make(__('admin.stats.today_orders'), $todayOrders)
                ->description(__('admin.stats.today_total'))
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make(__('admin.stats.pending_orders'), $pendingOrders)
                ->description(__('admin.stats.now_pending'))
                ->icon('heroicon-o-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make(__('admin.stats.active_restaurants'), $restaurants)
                ->description(__('admin.stats.working'))
                ->icon('heroicon-o-building-storefront')
                ->color('success'),

            Stat::make(__('admin.stats.available_couriers'), $couriers)
                ->description(__('admin.stats.online_free'))
                ->icon('heroicon-o-truck')
                ->color('info'),

            Stat::make(__('admin.stats.monthly_revenue'), number_format($monthRevenue, 0, '.', ' ') . " so'm")
                ->description(now()->format('F Y'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
