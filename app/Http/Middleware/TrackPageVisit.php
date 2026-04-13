<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;

class TrackPageVisit
{
    // Routes to skip (admin, api, assets, non-GET)
    private const SKIP_PREFIXES = ['admin', 'api', 'lang', 'cookie-consent', 'cart', 'checkout', 'logout'];
    private const SKIP_EXTENSIONS = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'map'];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests that return HTML (2xx)
        if (!$request->isMethod('GET') || !$response->isSuccessful()) {
            return $response;
        }

        $path = ltrim($request->path(), '/');

        // Skip admin, API, asset paths
        foreach (self::SKIP_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $response;
            }
        }

        // Skip files with extensions
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext && in_array($ext, self::SKIP_EXTENSIONS)) {
            return $response;
        }

        PageVisit::create([
            'path'        => '/' . $path ?: '/',
            'page_title'  => null,
            'user_id'     => auth()->id(),
            'session_id'  => session()->getId(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'device_type' => $this->detectDevice($request->userAgent() ?? ''),
            'referer'     => $request->header('referer'),
        ]);

        return $response;
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);

        if (preg_match('/tablet|ipad|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
