<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\DeleteAccountController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

// ─── Authentication ────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/otp/send',   [OtpController::class,  'send']);
    Route::post('/otp/verify', [OtpController::class,  'verify']);
    Route::post('/login',      [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);
    });
});

// ─── Catalog (public) ──────────────────────────────────────────────────────────
Route::prefix('catalog')->group(function () {
    Route::get('/shop-categories',  [CatalogController::class, 'shopCategories']);
    Route::get('/advertisements',   [CatalogController::class, 'advertisements']);
    Route::get('/restaurants',      [CatalogController::class, 'restaurants']);
    Route::get('/restaurants/{id}', [CatalogController::class, 'restaurant']);
    Route::get('/categories',       [CatalogController::class, 'categories']);
    Route::get('/products',         [CatalogController::class, 'products']);
    Route::get('/products/{id}',    [CatalogController::class, 'product']);
});

// ─── App config (public) ──────────────────────────────────────────────────────
Route::get('/config', [ConfigController::class, 'index']);

// ─── Account deletion (public — OTP is the verification) ──────────────────────
Route::prefix('account')->group(function () {
    Route::post('/delete/request', [DeleteAccountController::class, 'request']);
    Route::post('/delete/confirm', [DeleteAccountController::class, 'confirm']);
});

// ─── Orders (requires auth) ────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::get('/',              [OrderController::class, 'index']);
    Route::post('/',             [OrderController::class, 'store']);
    Route::get('/active',        [OrderController::class, 'active']);
    Route::get('/{id}',          [OrderController::class, 'show']);
    Route::post('/{id}/cancel',  [OrderController::class, 'cancel']);
});
