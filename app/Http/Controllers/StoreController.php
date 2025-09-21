<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Rifa;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // ====== AÑADIDO: Procesar quickSelections (selecciones rápidas) ======
    $quickSelections = [];
    // Debug para ver qué tiene la rifa
    \Log::info('Quick selections raw:', ['data' => $rifa->quick_selections]);
    
    if ($rifa->quick_selections && is_array($rifa->quick_selections)) {
        foreach ($rifa->quick_selections as $sel) {
            if (!empty($sel['cantidad']) && $sel['cantidad'] > 0) {
                $quickSelections[] = [
                    'cantidad' => (int)$sel['cantidad'],
                    'etiqueta' => $sel['etiqueta'] ?? ($sel['cantidad'] . ' Tickets'),
                    'descuento' => (float)($sel['descuento'] ?? 0),
                ];
            }
        }
    }
    
    \Log::info('Quick selections procesadas:', ['data' => $quickSelections]);

    return view('store.rifa', [
        'tenant' => $tenant,
        'rifa' => $rifa,
        'numeros' => $numeros,
        'brand' => $brand,
        'contact' => $contact,
        'paymentAccounts' => $paymentAccounts,
        'tasaBs' => $tenant->tasa_bs ?? null,
        'quickSelections' => $quickSelections, // ASEGURARSE QUE ESTO SE PASE
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

    /**
     * Obtener números aleatorios disponibles SIN reservarlos definitivamente
     * POST /t/{tenant}/rifas/{rifa}/get-available-numbers
     * 
     * Este método SOLO encuentra números disponibles pero NO los reserva
     * La reserva real se hace en CheckoutController::storeReservation
     */
    public function getAvailableNumbers(Request $request, Tenant $tenant, Rifa $rifa)
    {
        try {
            $request->validate([
                'cantidad' => 'required|integer|min:1|max:100',
                'tipo' => 'required|in:aleatorio,verificar'
            ]);

            $cantidad = $request->input('cantidad');

            // Liberar reservas expiradas antes de buscar disponibles
            DB::table('rifa_numeros')
                ->where('rifa_id', $rifa->id)
                ->where('estado', 'reservado')
                ->whereNotNull('reservado_hasta')
                ->where('reservado_hasta', '<', now())
                ->update([
                    'estado' => 'disponible',
                    'reservado_hasta' => null,
                    'session_id' => null,
                    'client_info' => null,
                    'updated_at' => now(),
                ]);

            // Consultar números disponibles SIN RESERVARLOS
            $availableNumbers = DB::table('rifa_numeros')
                ->where('rifa_id', $rifa->id)
                ->where('estado', 'disponible')
                ->where(function($q) {
                    $q->whereNull('reservado_hasta')
                      ->orWhere('reservado_hasta', '<', now());
                })
                ->inRandomOrder()
                ->limit($cantidad * 2) // Obtener el doble por si algunos se toman
                ->pluck('numero')
                ->take($cantidad)
                ->toArray();

            // Si no hay suficientes números disponibles
            if (count($availableNumbers) < $cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => "Solo hay " . count($availableNumbers) . " números disponibles",
                    'available_count' => count($availableNumbers),
                    'requested' => $cantidad
                ], 200);
            }

            // NO RESERVAR AQUÍ - Solo devolver los números disponibles
            // La reserva real se hará en CheckoutController::storeReservation
            
            return response()->json([
                'success' => true,
                'numbers' => $availableNumbers,
                'message' => 'Números seleccionados. Procede al pago para reservarlos.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al obtener números disponibles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar disponibilidad',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    // NOTA: El método reserve() ya NO es necesario aquí porque 
    // CheckoutController::storeReservation() maneja todo el proceso de reserva y pago
}