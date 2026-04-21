<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use BezhanSalleh\LanguageSwitch\Enums\Placement;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

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
