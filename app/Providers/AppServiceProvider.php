<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE,
            function (array $scopes) {
                $isFormPage = collect($scopes)->contains(
                    fn ($scope) =>
                        is_a($scope, CreateRecord::class, true) ||
                        is_a($scope, EditRecord::class, true)
                );

                if (! $isFormPage) {
                    return '';
                }

                return view('filament.components.lang-switcher-inline');
            },
        );
    }
}
