<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CheckoutController;

Route::get('/', fn () => view('welcome'));

Route::prefix('t/{tenant}')
    ->middleware('setTenant')
    ->group(function () {
        // Tienda
        Route::get('/', [StoreController::class, 'home'])->name('store.home');
        Route::get('/rifas', [StoreController::class, 'rifas'])->name('store.rifas');
        Route::get('/r/{rifa:slug}', [StoreController::class, 'rifa'])->name('store.rifa');

        // Reserva
        Route::post('/r/{rifa:slug}/reservar', [CheckoutController::class, 'storeReservation'])
            ->name('store.reserve');

        // Confirmación de reserva (NO checkout)
        Route::get('/reserva/{code}', [CheckoutController::class, 'reservationConfirm'])
            ->name('store.reserve.confirm');

        // Checkout / Pago
        Route::get('/checkout/{code}', [CheckoutController::class, 'show'])->name('store.checkout');
        Route::post('/checkout/{code}/pagar', [CheckoutController::class, 'pay'])->name('store.pay');

        // Después de la línea de checkout
Route::get('/checkout/{code}/confirmacion', [CheckoutController::class, 'confirmation'])
    ->name('store.checkout.confirmation');
    
        // Alias en inglés para clientes/JS que apunten a /pay
        Route::post('/checkout/{code}/pay', [CheckoutController::class, 'pay'])->name('store.checkout.pay');

        // ✅ **AQUÍ VA EL VERIFICADOR**
        Route::get('/verify', [StoreController::class, 'verify'])->name('store.verify');
    });
