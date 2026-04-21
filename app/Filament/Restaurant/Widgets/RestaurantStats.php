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

        $totalProducts = Product::where('restaurant_id', $restaurantId)->count();

        $totalCategories = Category::where('status', 'active')->count();

        return [
            Stat::make('Bugungi buyurtmalar', $todayOrders)
                ->description('Bekor qilinmagan')
                ->color('success'),
            Stat::make('Faol buyurtmalar', $pendingOrders)
                ->description('Bajarilishi kutilmoqda')
                ->color('warning'),
            Stat::make('Mahsulotlar', $totalProducts)
                ->description('Jami')
                ->color('info'),
            Stat::make('Kategoriyalar', $totalCategories)
                ->description('Jami')
                ->color('primary'),
        ];
    }
}
