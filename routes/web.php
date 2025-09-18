<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublicContractController;
use App\Http\Controllers\ClientContractController;
use App\Http\Controllers\SitemapController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================
// PÁGINAS PÚBLICAS
// ====================================

// Landing Page Principal
Route::get('/', fn () => view('landing'))->name('home');

// Páginas Legales y de Información
Route::view('/contacto', 'contact')->name('contact');
Route::view('/terminos', 'terms')->name('terms');
Route::view('/privacidad', 'privacy')->name('privacy');

// Sitemap XML Dinámico
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Robots.txt Dinámico
Route::get('/robots.txt', function () {
    $robots = "# Robots.txt for Rifasys.com\n";
    $robots .= "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /admin/\n";
    $robots .= "Disallow: /t/*/checkout/\n";
    $robots .= "Disallow: /t/*/admin/\n";
    $robots .= "Disallow: /contrato/firma/\n";
    $robots .= "Disallow: /mi-contrato\n";
    $robots .= "\n";
    $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";
    
    return response($robots, 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

// ====================================
// CONTRATOS DE SERVICIO
// ====================================

// Ruta pública para firma de contrato (link seguro enviado por superadmin)
Route::get('/contrato/firma/{uuid}', [PublicContractController::class, 'show'])
    ->name('contrato.firma.show');

Route::post('/contrato/firma/{uuid}', [PublicContractController::class, 'accept'])
    ->name('contrato.firma.aceptar');

// Ruta privada: "Mi Contrato" para el cliente (requiere login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mi-contrato', [ClientContractController::class, 'show'])->name('client.contract');
});

// ====================================
// RUTAS MULTI-TENANT
// ====================================

Route::prefix('t/{tenant}')
    ->middleware('setTenant')
    ->group(function () {
        // Tienda Principal
        Route::get('/', [StoreController::class, 'home'])->name('store.home');
        Route::get('/rifas', [StoreController::class, 'rifas'])->name('store.rifas');
        Route::get('/r/{rifa:slug}', [StoreController::class, 'rifa'])->name('store.rifa');

        // Proceso de Reserva
        Route::post('/r/{rifa:slug}/reservar', [CheckoutController::class, 'storeReservation'])
            ->name('store.reserve');

        // Confirmación de reserva (NO checkout)
        Route::get('/reserva/{code}', [CheckoutController::class, 'reservationConfirm'])
            ->name('store.reserve.confirm');

        // Proceso de Checkout / Pago
        Route::get('/checkout/{code}', [CheckoutController::class, 'show'])->name('store.checkout');
        Route::post('/checkout/{code}/pagar', [CheckoutController::class, 'pay'])->name('store.pay');
        
        // Confirmación post-pago
        Route::get('/checkout/{code}/confirmacion', [CheckoutController::class, 'confirmation'])
            ->name('store.checkout.confirmation');
        
        // Alias en inglés para compatibilidad con APIs/JS
        Route::post('/checkout/{code}/pay', [CheckoutController::class, 'pay'])->name('store.checkout.pay');

        // Verificador de Tickets
        Route::get('/verify', [StoreController::class, 'verify'])->name('store.verify');
    });

// ====================================
// FALLBACK 404 (opcional)
// ====================================

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});