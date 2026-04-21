<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use App\Filament\Restaurant\Widgets\RestaurantStats;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RestaurantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('restaurant')
            ->path('restaurant')
            ->login()
            ->authGuard('restaurant')
            ->darkMode(true)
            ->colors(['primary' => Color::Orange])
            ->brandName('VipOnlineMarket — Restoran')
            ->discoverResources(in: app_path('Filament/Restaurant/Resources'), for: 'App\Filament\Restaurant\Resources')
            ->discoverPages(in: app_path('Filament/Restaurant/Pages'), for: 'App\Filament\Restaurant\Pages')
            ->pages([Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Restaurant/Widgets'), for: 'App\Filament\Restaurant\Widgets')
            ->widgets([RestaurantStats::class])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
