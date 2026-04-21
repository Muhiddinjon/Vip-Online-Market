@php
    $languageSwitch = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $languageSwitch->getOutsidePanelPlacement()->value;

    // Outside panels: open dropdown inward so it stays on screen
    $placement = match (true) {
        $isVisibleOutsidePanels && str_contains($outsidePanelsPlacement, 'right')  => 'bottom-start',
        $isVisibleOutsidePanels && str_contains($outsidePanelsPlacement, 'left')   => 'bottom-end',
        $isVisibleOutsidePanels && str_contains($outsidePanelsPlacement, 'center') => 'bottom',
        __('filament-panels::layout.direction') === 'rtl'                          => 'bottom-start',
        default                                                                     => 'bottom-end',
    };

    $maxHeight = $languageSwitch->getMaxHeight();
@endphp

@if ($isVisibleOutsidePanels)
    {{-- Fixed top-right corner on login/auth pages --}}
    <div style="position:fixed; top:1rem; right:1rem; z-index:9999;">
        @include('language-switch::switch')
    </div>
@else
    {{-- Inside panel topbar: small right margin so it doesn't touch the avatar --}}
    <div style="display:flex; align-items:center; margin-right:0.5rem;">
        @include('language-switch::switch')
    </div>
@endif
