<?php
namespace App\Filament\Restaurant\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantStats extends BaseWidget
{
    protected function getStats(): array
    {
        $restaurantId = auth()->user()?->restaurant?->id;

        $todayOrders = Order::where('restaurant_id', $restaurantId)
            ->whereDate('created_at', today())
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $pendingOrders = Order::where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->count();

        $totalProducts  = Product::where('restaurant_id', $restaurantId)->count();
        $totalCategories = Category::where('status', 'active')->count();

        return [
            Stat::make(__('admin.stats.today_orders'), $todayOrders)
                ->description(__('admin.stats.cancelled_not'))
                ->color('success'),
            Stat::make(__('admin.order.status_pending'), $pendingOrders)
                ->description(__('admin.stats.in_progress'))
                ->color('warning'),
            Stat::make(__('admin.nav.products'), $totalProducts)
                ->description(__('admin.stats.total'))
                ->color('info'),
            Stat::make(__('admin.nav.categories'), $totalCategories)
                ->description(__('admin.stats.total'))
                ->color('primary'),
        ];
    }
}
