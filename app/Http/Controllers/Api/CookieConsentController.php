<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CookieConsentResource;
use App\Models\CookieConsent;
use Illuminate\Http\Request;

class CookieConsentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'analytics' => 'required|boolean',
            'marketing' => 'required|boolean',
            'choice'    => 'required|string|max:50',
            'locale'    => 'nullable|string|max:5',
        ]);

        $consent = CookieConsent::create([
            'session_id' => $request->input('session_id', (string) \Str::uuid()),
            'ip_address' => $request->ip(),
            'locale'     => $data['locale'] ?? app()->getLocale(),
            'analytics'  => $data['analytics'],
            'marketing'  => $data['marketing'],
            'choice'     => $data['choice'],
        ]);

        return (new CookieConsentResource($consent))->response()->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $query = CookieConsent::latest();

        if ($request->filled('choice')) {
            $query->where('choice', $request->choice);
        }

        return CookieConsentResource::collection($query->paginate(50));
    }
}
