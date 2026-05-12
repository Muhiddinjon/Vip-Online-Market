<?php

use App\Http\Controllers\OrderPrintController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/locale/switch', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['uz', 'tr', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('locale.switch');

Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/print', [OrderPrintController::class, 'show'])->name('orders.print');
});

