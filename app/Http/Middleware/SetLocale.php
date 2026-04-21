<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = session('locale', config('app.locale', 'uz'));

        if (in_array($locale, ['uz', 'tr', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
