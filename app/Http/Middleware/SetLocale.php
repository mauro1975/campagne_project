<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'it'));

        if (!in_array($locale, ['en', 'it'])) {
            $locale = 'it';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
