<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use BezhanSalleh\LanguageSwitch\Enums\Placement;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['uz', 'tr', 'en'])
                ->labels([
                    'uz' => "O'zbekcha",
                    'tr' => 'Türkçe',
                    'en' => 'English',
                ])
                ->flags([
                    'uz' => asset('flags/uz.svg'),
                    'tr' => asset('flags/tr.svg'),
                    'en' => asset('flags/en.svg'),
                ])
                ->circular()
                ->renderHook('panels::topbar.end')
                ->visible(outsidePanels: true)
                ->outsidePanelPlacement(Placement::TopRight)
                ->outsidePanelsRenderHook('panels::body.start');
        });
    }
}
