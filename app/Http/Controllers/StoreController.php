<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Rifa;
use App\Models\PaymentAccount;

class StoreController extends Controller
{
    /** Home pública del tenant */
    public function home(Tenant $tenant)
    {
        $brand   = $tenant->brandSettings()->first();
        $home    = $tenant->homeSettings()->first();
        $contact = $tenant->contactSettings()->first();

        // CTA a la última rifa ACTIVA de este tenant
        $rifaActiva = $tenant->rifas()->where('estado', 'activa')->latest('id')->first();
        $ctaUrl = $rifaActiva
            ? route('store.rifa', ['tenant' => $tenant, 'rifa' => $rifaActiva->slug])
            : null;

        return view('store.home', compact('brand','home','contact','ctaUrl'));
    }

    /** (Opcional) listado de rifas del tenant */
    public function rifas(Tenant $tenant)
    {
        $brand   = $tenant->brandSettings()->first();
        $contact = $tenant->contactSettings()->first();

        $rifas = $tenant->rifas()
            ->whereIn('estado', ['activa','pausada','finalizada'])
            ->latest('id')
            ->get();

        // Si aún no tienes una vista de listado, puedes crearla luego.
        // Por ahora devolvemos la home con CTA; cambia a tu vista cuando la tengas:
        return view('store.home', compact('brand','contact')) + ['home' => null, 'ctaUrl' => null, 'rifas' => $rifas];
    }

    /**
     * Detalle de rifa con selección de números
     */
    public function rifa(Tenant $tenant, Rifa $rifa)
    {
        // Seguridad: la rifa debe pertenecer al tenant del prefijo
        if ($rifa->tenant_id !== $tenant->id) {
            abort(404);
        }

        // Ajustes de marca y contacto del tenant
        $brand   = $tenant->brandSettings()->first();
        $contact = $tenant->contactSettings()->first();

        // Números para la grilla
        $numeros = $rifa->numeros()
            ->orderBy('numero')
            ->get(['numero','estado']);

        // Métodos de pago activos del tenant (para el checkout inline)
        $paymentAccounts = PaymentAccount::query()
            ->where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->orderBy('etiqueta')
            ->get([
                'id',
                'etiqueta',
                'logo',
                'tipo',
                'banco',
                'numero',
                'iban',
                'titular',
                'documento',
                'email',
                'wallet',
                'red',
                'notes',
                'requiere_voucher'
            ]);

        // Enviamos todo lo necesario a la vista
        return view('store.rifa', [
    'tenant'           => $tenant,
    'rifa'             => $rifa,
    'numeros'          => $numeros,
    'brand'            => $brand,
    'contact'          => $contact,
    'paymentAccounts'  => $paymentAccounts,
    'tasaBs'           => $tenant->tasa_bs ?? null,
]);

    }

    /**
 * Verificar ticket por código
 */
public function verify(Tenant $tenant, \Illuminate\Http\Request $request)
{
    $code = trim((string) $request->query('code', ''));
    $order = null;
    $rifa = null;
    $qrCode = null;

    if ($code !== '') {
        $order = \App\Models\Order::with(['rifa','items'])
            ->where('tenant_id', $tenant->id)
            ->where('code', $code)
            ->first();

        // Si hay orden, busca la rifa asociada para mostrar el banner y estado
        if ($order) {
            $rifa = $order->rifa;
            
            // Generar QR de la URL actual para compartir
            $currentUrl = $request->fullUrl();
            try {
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($currentUrl);
            } catch (\Exception $e) {
                \Log::warning('Error generando QR en verificación: ' . $e->getMessage());
                $qrCode = null;
            }
        } else {
            // También puedes buscar la rifa activa para mostrar el banner por defecto
            $rifa = $tenant->rifas()->where('estado','activa')->latest('id')->first();
        }
    } else {
        // Sin código, solo busca la rifa activa (para mostrar el banner)
        $rifa = $tenant->rifas()->where('estado','activa')->latest('id')->first();
    }

    // Siempre pásalo a la vista
    $contact = $tenant->contactSettings()->first();

    return view('store.verify', compact('tenant', 'order', 'code', 'rifa', 'contact', 'qrCode'));
}


}
