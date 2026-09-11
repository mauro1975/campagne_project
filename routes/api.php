<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\GalleryPhotoController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmailCampaignController;
use App\Http\Controllers\Api\CookieConsentController;
use App\Http\Controllers\Api\PageVisitController;

/*
|--------------------------------------------------------------------------
| API Routes – Laravel Passport (Bearer token)
| Base URL: /api
|--------------------------------------------------------------------------
*/

// ── Auth (public) ─────────────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ── Catalog (public) ────────────────────────────────────────────────────
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/gallery', [GalleryPhotoController::class, 'index']);

// ── Cart (guest or authenticated) ───────────────────────────────────────
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::put('/items/{item}', [CartController::class, 'update']);
    Route::delete('/items/{item}', [CartController::class, 'remove']);
    Route::delete('/', [CartController::class, 'clear']);
    Route::post('/discount', [CartController::class, 'applyDiscount']);
});

// ── Orders (guest checkout + authenticated history) ─────────────────────
Route::post('/orders', [OrderController::class, 'store']);

// ── Cookie consent (public) ───────────────────────────────────────────────
Route::post('/cookie-consent', [CookieConsentController::class, 'store']);

// ── Authenticated users ───────────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});

// ── Admin (Passport + admin role) ───────────────────────────────────────
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Products
    Route::get('/products', [AdminController::class, 'products']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::patch('/products/{product}', [AdminController::class, 'updateProduct']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products/{product}/image', [ProductController::class, 'uploadImage']);

    // Product variants
    Route::get('/products/{product}/variants', [ProductVariantController::class, 'index']);
    Route::post('/products/{product}/variants', [ProductVariantController::class, 'store']);
    Route::put('/products/{product}/variants/{variant}', [ProductVariantController::class, 'update']);
    Route::delete('/products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy']);

    // Orders
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::put('/orders/{order}', [AdminController::class, 'updateOrder']);

    // Discounts
    Route::apiResource('discounts', DiscountController::class);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Gallery
    Route::get('/gallery', [GalleryPhotoController::class, 'adminIndex']);
    Route::post('/gallery', [GalleryPhotoController::class, 'store']);
    Route::put('/gallery/{galleryPhoto}', [GalleryPhotoController::class, 'update']);
    Route::delete('/gallery/{galleryPhoto}', [GalleryPhotoController::class, 'destroy']);

    // Email campaigns
    Route::apiResource('campaigns', EmailCampaignController::class);

    // Analytics
    Route::get('/page-visits', [PageVisitController::class, 'index']);
    Route::get('/page-visits/stats', [PageVisitController::class, 'stats']);
    Route::get('/cookie-consents', [CookieConsentController::class, 'index']);
});
