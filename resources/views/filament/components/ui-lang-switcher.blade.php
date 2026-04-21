<div
    x-data="{
        open: false,
        current: '{{ session('locale', config('app.locale', 'uz')) }}',
        langs: {
            uz: { label: 'O\'zbekcha', flag: '🇺🇿' },
            tr: { label: 'Türkçe',    flag: '🇹🇷' },
            en: { label: 'English',   flag: '🇬🇧' },
        }
    }"
    x-on:click.outside="open = false"
    class="relative flex items-center mr-2 fi-ui-lang-sw"
>
    <button
        type="button"
        x-on:click="open = !open"
        title="Switch language"
        class="h-9 w-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-xl shadow-sm transition hover:bg-gray-50 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-1"
    >
        <span x-text="langs[current].flag" class="leading-none select-none"></span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 top-full z-50 mt-2 w-40 origin-top-right rounded-xl border border-gray-200 bg-white shadow-lg ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800"
        style="display:none"
    >
        @foreach(['uz' => ['🇺🇿', "O'zbekcha"], 'tr' => ['🇹🇷', 'Türkçe'], 'en' => ['🇬🇧', 'English']] as $code => $info)
        <form method="POST" action="{{ route('locale.switch') }}">
            @csrf
            <input type="hidden" name="locale" value="{{ $code }}">
            <button
                type="submit"
                x-on:click="current = '{{ $code }}'; open = false"
                class="flex w-full items-center gap-2.5 px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50 first:rounded-t-xl last:rounded-b-xl dark:text-gray-300 dark:hover:bg-gray-700 {{ session('locale', config('app.locale', 'uz')) === $code ? 'font-semibold bg-gray-50 dark:bg-gray-700' : '' }}"
            >
                <span class="text-base leading-none">{{ $info[0] }}</span>
                <span>{{ $info[1] }}</span>
            </button>
        </form>
        @endforeach
    </div>
</div>
