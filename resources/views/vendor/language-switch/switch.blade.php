@php
    $resolvedRenderHook = $languageSwitch->getRenderHook();
    $shouldTeleport = ! str_contains($resolvedRenderHook, '::sidebar.')
        && ! str_contains($resolvedRenderHook, 'user-menu.');
@endphp

<x-filament::dropdown
    :teleport="$shouldTeleport"
    :placement="$placement"
    :max-height="$maxHeight"
    class="fi-dropdown fi-user-menu"
    data-nosnippet="true"
>
    <x-slot name="trigger">
        <div
            style="
                display: flex;
                align-items: center;
                justify-content: center;
                width: 2.25rem;
                height: 2.25rem;
                padding: 3px;
                border-radius: 9999px;
                background: transparent;
                box-shadow: 0 0 0 1.5px #e5e7eb;
                cursor: pointer;
                transition: box-shadow 0.15s;
            "
            onmouseover="this.style.boxShadow='0 0 0 1.5px #9ca3af'"
            onmouseout="this.style.boxShadow='0 0 0 1.5px #e5e7eb'"
            x-tooltip="{
                content: @js($languageSwitch->getLabel(app()->getLocale())),
                theme: $store.theme,
                placement: document.dir === 'rtl' ? 'left' : 'right',
            }"
        >
            @if ($isFlagsOnly || $hasFlags)
                <x-language-switch::flag
                    :src="$languageSwitch->getFlag(app()->getLocale())"
                    :circular="$isCircular"
                    :alt="$languageSwitch->getLabel(app()->getLocale())"
                    :switch="true"
                />
            @else
                <span style="font-weight:600; font-size:0.875rem; color: var(--primary-600);">
                    {{ $languageSwitch->getCharAvatar(app()->getLocale()) }}
                </span>
            @endif
        </div>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($locales as $locale)
            @if (!app()->isLocale($locale))
                <button
                    type="button"
                    wire:click="changeLocale('{{ $locale }}')"
                    class="fi-dropdown-list-item fi-dropdown-list-item-color-gray"
                    style="
                        display: flex;
                        align-items: center;
                        gap: 0.625rem;
                        width: 100%;
                        padding: 0.375rem 0.5rem;
                        border-radius: 0.375rem;
                        text-align: left;
                        white-space: nowrap;
                        cursor: pointer;
                        background: transparent;
                        border: none;
                        transition: background 0.075s;
                    "
                    onmouseover="this.style.background='rgba(0,0,0,0.04)'"
                    onmouseout="this.style.background='transparent'"
                >
                    @if ($hasFlags)
                        <x-language-switch::flag
                            :src="$languageSwitch->getFlag($locale)"
                            :circular="$isCircular"
                            :alt="$languageSwitch->getLabel($locale)"
                        />
                    @else
                        <span style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            width: 1.75rem;
                            height: 1.75rem;
                            font-size: 0.75rem;
                            font-weight: 600;
                            border-radius: {{ $isCircular ? '9999px' : '0.5rem' }};
                            background: rgba(var(--primary-500), 0.1);
                            color: var(--primary-600);
                            flex-shrink: 0;
                        ">{{ $languageSwitch->getCharAvatar($locale) }}</span>
                    @endif

                    <span style="font-size:0.875rem; font-weight:500; color:#4b5563;">
                        {{ $languageSwitch->getLabel($locale) }}
                    </span>
                </button>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
