<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CookieConsent;
use App\Models\GalleryPhoto;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $featured   = Product::where('is_featured', true)->where('is_active', true)->limit(8)->get();
        $bestSellers = Product::where('is_best_seller', true)->where('is_active', true)->limit(4)->get();
        $galleryPhotos = GalleryPhoto::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        return view('home', compact('categories', 'featured', 'bestSellers', 'galleryPhotos'));
    }

    public function account()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', auth()->id())->with('items')->latest()->get();
        return view('account.orders', compact('orders'));
    }

    public function sitemap()
    {
        $categories = Category::where('is_active', true)->get();
        $products   = Product::where('is_active', true)->get();
        $content    = view('sitemap', compact('categories', 'products'))->render();
        return Response::make($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function cookieConsent(Request $request)
    {
        $choice    = $request->input('choice', 'necessary'); // 'all' | 'custom' | 'necessary'
        $analytics = $request->boolean('analytics', $choice === 'all');
        $marketing = $request->boolean('marketing', $choice === 'all');

        $request->session()->put('cookie_consent', true);
        $request->session()->put('cookie_analytics', $analytics);
        $request->session()->put('cookie_marketing', $marketing);

        // Upsert: one record per session
        CookieConsent::updateOrCreate(
            ['session_id' => $request->session()->getId()],
            [
                'ip_address' => $request->ip(),
                'locale'     => app()->getLocale(),
                'analytics'  => $analytics,
                'marketing'  => $marketing,
                'choice'     => $choice,
            ]
        );

        return response()->json(['ok' => true]);
    }
}
