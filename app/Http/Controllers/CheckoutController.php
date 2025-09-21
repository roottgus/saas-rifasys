<?php 

namespace App\Http\Controllers;

use App\Models\{Tenant, Rifa, RifaNumero, Order, OrderItem, PaymentAccount};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Enums\NumeroEstado;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Mail\PaymentSubmittedMail;
use App\Services\PaymentAccountService;

class CheckoutController extends Controller
{
    /**
 * Versión mejorada de storeReservation en CheckoutController
 * que maneja automáticamente cuando los números no están disponibles
 */
public function storeReservation(Tenant $tenant, Rifa $rifa, Request $request)
{
    // Detectar flujo: reserve | pay
    $flow     = strtolower($request->input('flow', $request->boolean('pay_now') ? 'pay' : 'reserve'));
    $isPayNow = in_array($flow, ['pay', 'pay-now', 'pagar'], true);

    if ($rifa->tenant_id !== $tenant->id) {
        abort(404);
    }

    if (($rifa->estado ?? null) !== 'activa') {
        return response()->json(['ok' => false, 'message' => 'Esta rifa no está disponible en este momento.'], 422);
    }

    $numbers = collect((array) $request->input('numbers', []))
        ->map(fn ($n) => (int) $n)
        ->filter(fn ($n) => $n > 0)
        ->unique()
        ->values();

    if ($numbers->isEmpty()) {
        return response()->json(['ok' => false, 'message' => 'Debes enviar al menos un número.'], 422);
    }

    $cantidadSolicitada = $numbers->count();
    $min = (int) ($rifa->min_por_compra ?? 1);
    $max = (int) ($rifa->max_por_compra ?? PHP_INT_MAX);
    
    if ($cantidadSolicitada < $min) {
        return response()->json(['ok' => false, 'message' => "Debes seleccionar al menos {$min} números."], 422);
    }
    if ($cantidadSolicitada > $max) {
        return response()->json(['ok' => false, 'message' => "Máximo {$max} números por compra."], 422);
    }

    $minutes = (int) $request->integer('minutes', 240);
    $minutes = max(5, min(240, $minutes));

    $customer_name  = $request->input('nombre', $request->input('customer_name'));
    $customer_phone = $request->input('whatsapp', $request->input('customer_whatsapp', $request->input('customer_phone')));
    $customer_email = $request->input('email', $request->input('customer_email'));

    try {
    // Variable para almacenar los números reservados (fuera del transaction)
    $reservedNumbers = [];
    
    $order = DB::transaction(function () use ($tenant, $rifa, $numbers, $minutes, $customer_name, $customer_phone, $customer_email, $isPayNow, $request, $cantidadSolicitada, &$reservedNumbers) {
        
        // Primer intento: verificar los números solicitados
        $rows = RifaNumero::where('rifa_id', $rifa->id)
            ->whereIn('numero', $numbers)
            ->lockForUpdate()
            ->get(['id', 'numero', 'estado']);

        // Verificar cuáles están disponibles
        $disponibles = $rows->filter(fn ($r) => $r->estado === NumeroEstado::Disponible);
        $numerosDisponibles = $disponibles->pluck('numero');
        
        // Si no todos están disponibles, buscar alternativas
        if ($disponibles->count() < $cantidadSolicitada) {
            \Log::info('No todos los números están disponibles, buscando alternativas', [
                'solicitados' => $numbers->toArray(),
                'disponibles' => $numerosDisponibles->toArray(),
                'faltantes' => $cantidadSolicitada - $disponibles->count()
            ]);
            
            $faltantes = $cantidadSolicitada - $disponibles->count();
            
            // Buscar números alternativos disponibles
            $alternativos = RifaNumero::where('rifa_id', $rifa->id)
                ->where('estado', NumeroEstado::Disponible)
                ->whereNotIn('numero', $numerosDisponibles)
                ->inRandomOrder()
                ->limit($faltantes * 2)
                ->lockForUpdate()
                ->get(['id', 'numero', 'estado']);
            
            $alternativosDisponibles = $alternativos->filter(fn ($r) => $r->estado === NumeroEstado::Disponible)
                ->take($faltantes);
            
            $todosLosNumeros = $disponibles->concat($alternativosDisponibles);
            
            if ($todosLosNumeros->count() < $cantidadSolicitada) {
                $totalDisponibles = RifaNumero::where('rifa_id', $rifa->id)
                    ->where('estado', NumeroEstado::Disponible)
                    ->count();
                
                throw new \Exception("Solo hay {$totalDisponibles} números disponibles en total. Por favor, reduce la cantidad.");
            }
            
            $rows = $todosLosNumeros;
            $numerosFinales = $todosLosNumeros->pluck('numero');
            
            \Log::info('Números finales asignados', [
                'originales' => $numerosDisponibles->toArray(),
                'alternativos' => $alternativosDisponibles->pluck('numero')->toArray(),
                'finales' => $numerosFinales->toArray()
            ]);
        } else {
            $numerosFinales = $numerosDisponibles;
        }

        // IMPORTANTE: Guardar los números en la variable por referencia
        $reservedNumbers = $numerosFinales->toArray();

        // Continuar con la creación de la orden
        $now   = Carbon::now('America/Caracas');
        $until = $now->copy()->addMinutes($minutes);

        $code  = strtoupper(Str::random(8));
        $total = $numerosFinales->count() * (float) $rifa->precio;

        $order = new Order();
        $order->tenant_id      = $tenant->id;
        $order->rifa_id        = $rifa->id;
        $order->code           = $code;
        $order->status         = OrderStatus::Pending;
        $order->total_amount   = $total;
        $order->expires_at     = $until;
        $order->customer_name  = $customer_name;
        $order->customer_phone = $customer_phone;
        $order->customer_email = $customer_email;
        $order->save();

        // Si es pago directo y hay voucher, guardarlo
        if ($isPayNow && $request->hasFile('voucher')) {
            $path = $request->file('voucher')->store("vouchers/{$order->id}", 'public');
            $order->voucher_path = $path;
            $order->save();
        }

        // Crear los items con los números finales
        $items = [];
        foreach ($rows as $r) {
            $items[] = [
                'tenant_id'  => $tenant->id,
                'order_id'   => $order->id,
                'rifa_id'    => $rifa->id,
                'numero'     => $r->numero,
                'price'      => (float) $rifa->precio,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        OrderItem::insert($items);

        // Actualizar el estado de los números a reservado
        RifaNumero::whereIn('id', $rows->pluck('id'))->update([
            'estado'          => NumeroEstado::Reservado,
            'reservado_hasta' => $until,
            'updated_at'      => $now,
        ]);

        // NO intentar guardar reserved_numbers en la BD
        return $order;
    }, 3);
    
} catch (\Throwable $e) {
    report($e);
    return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
}

    // Si el resultado de la transacción es una respuesta JSON (error), devolverla
    if ($order instanceof \Illuminate\Http\JsonResponse) {
        return $order;
    }

 
// Construir redirect según flujo
$redirect = $isPayNow
    ? route('store.checkout', [
        'tenant' => $tenant->slug ?? $tenant->id,
        'code'   => $order->code,
      ])
    : route('store.reserve.confirm', [
        'tenant' => $tenant->slug ?? $tenant->id,
        'code'   => $order->code,
      ]);

return response()->json([
    'ok'               => true,
    'code'             => $order->code,
    'redirect'         => $redirect,
    'flow'             => $isPayNow ? 'pay' : 'reserve',
    'reserved_numbers' => $reservedNumbers,
    'message'          => count($reservedNumbers) === $cantidadSolicitada
        ? 'Números reservados exitosamente'
        : 'Se asignaron números alternativos disponibles',
]);

}

    /** Muestra el checkout de la orden */
    public function show(Tenant $tenant, string $code)
{
    

    \Log::info('CheckoutController@show USING FILE', ['file' => __FILE__]);

    $order = Order::where('tenant_id', $tenant->id)
        ->where('code', $code)
        ->with('items')
        ->first();

    if (!$order) {
        return response()->view('store.checkout-error', [
            'tenant'      => $tenant,
            'title'       => 'Orden no encontrada',
            'description' => 'El enlace de pago es incorrecto, ya expiró o nunca existió.',
        ], 404);
    }

    if ($order->status === OrderStatus::Pending && $order->expires_at && $order->expires_at->isPast()) {
        return response()->view('store.checkout-error', [
            'tenant'      => $tenant,
            'title'       => 'Reserva expirada',
            'description' => 'El enlace de pago ya venció.',
        ], 410);
    }

    if ($order->status === OrderStatus::Paid) {
        return response()->view('store.checkout-error', [
            'tenant' => $tenant, 'title' => '¡Orden ya pagada!', 'description' => 'Esta orden ya fue procesada.',
        ], 200);
    }

    if ($order->status === OrderStatus::Cancelled) {
        return response()->view('store.checkout-error', [
            'tenant' => $tenant, 'title' => 'Orden cancelada', 'description' => 'Esta reserva fue cancelada/expirada.',
        ], 200);
    }

    if ($order->status === OrderStatus::Submitted) {
        return redirect()->route('store.checkout.confirmation', [
            'tenant' => $tenant->slug ?? $tenant->id, 'code' => $order->code,
        ]);
    }

    // (sólo por compatibilidad con otros parciales)
    $paymentAccounts = \App\Models\PaymentAccount::query()
        ->withoutGlobalScopes()
        ->where('tenant_id', $tenant->id)
        ->where(function ($q) { $q->where('activo', 1)->orWhereNull('activo'); })
        ->orderBy('id')
        ->get();

    // ===== JSON CRUDO desde SQL (no Eloquent) =====
    $rawRows = DB::table('payment_accounts')
        ->select([
            'id','tenant_id','logo','etiqueta','banco','numero','iban','titular','documento',
            'email','wallet','red','notes','requiere_voucher',
            'usd_enabled','bs_enabled','tasa_bs','activo',
        ])
        ->where('tenant_id', $tenant->id)
        ->where(function ($q) { $q->where('activo', 1)->orWhereNull('activo'); })
        ->orderBy('id')
        ->get();

        
        //dd($rawRows->toArray());


    \Log::info('accounts raw count', ['tenant_id' => $tenant->id, 'count' => $rawRows->count()]);

    $accountsForJs = $rawRows->map(function ($r) {
        $usd = ((int)($r->usd_enabled ?? 0)) === 1;
        $bs  = ((int)($r->bs_enabled  ?? 0)) === 1;
        $tx  = (isset($r->tasa_bs) && $r->tasa_bs !== '' && is_numeric($r->tasa_bs)) ? (float)$r->tasa_bs : null;

        return [
            'id'               => (int)$r->id,
            'logo'             => !empty($r->logo) ? asset('storage/' . $r->logo) : null,
            'etiqueta'         => $r->etiqueta ?? null,
            'banco'            => $r->banco ?? null,
            'numero'           => $r->numero ?? null,
            'iban'             => $r->iban ?? null,
            'titular'          => $r->titular ?? null,
            'documento'        => $r->documento ?? null,
            'email'            => $r->email ?? null,
            'wallet'           => $r->wallet ?? null,
            'red'              => $r->red ?? null,
            'notes'            => $r->notes ?? null,
            'requiere_voucher' => (bool)($r->requiere_voucher ?? false),

            'usd_enabled'      => $usd,
            'bs_enabled'       => $bs,
            'tasa_bs'          => $tx,
            'can_bs'           => $bs && $tx !== null && $tx > 0,

            'accepted_currencies' => array_values(array_filter([
                $usd ? 'USD' : null,
                ($bs && $tx !== null && $tx > 0) ? 'VES' : null,
            ])),
        ];
    })->values();

    \Log::info('accountsForJs (controller FINAL)', $accountsForJs->toArray());

    $best = $accountsForJs->firstWhere('can_bs', true)
        ?? $accountsForJs->firstWhere('usd_enabled', true)
        ?? $accountsForJs->first();
    $selectedAccountId = $best['id'] ?? null;

    return view('store.checkout', [
        'tenant'            => $tenant,
        'order'             => $order,
        'items'             => $order->items,
        'paymentAccounts'   => $paymentAccounts,  // solo para otros parciales
        'accountsForJs'     => $accountsForJs,
        'selectedAccountId' => $selectedAccountId,
        'tSlug'             => $tenant->slug,
        'tasaBs'            => $tenant->tasa_bs,
    ]);
}

    public function reservationConfirm(Tenant $tenant, string $code)
    {
        $order = Order::where('tenant_id', $tenant->id)
            ->where('code', $code)
            ->with('items')
            ->firstOrFail();

        if ($order->status === OrderStatus::Pending && $order->expires_at && $order->expires_at->isPast()) {
            return response()->view('store.checkout-error', [
                'tenant'      => $tenant,
                'title'       => 'Reserva expirada',
                'description' => 'Tu reserva venció y los boletos se liberaron.',
            ], 410);
        }

        return view('store.reservations.confirm', [
            'tenant' => $tenant,
            'order'  => $order,
        ]);
    }

    /**
     * Muestra la confirmación de pago procesado
     */
    public function confirmation(Tenant $tenant, string $code)
{
    // CAMBIO: Aceptar tanto Submitted como Paid
    $order = Order::where('tenant_id', $tenant->id)
        ->where('code', $code)
        ->whereIn('status', [OrderStatus::Submitted, OrderStatus::Paid]) // <- CAMBIO AQUÍ
        ->with(['items', 'paymentAccount', 'rifa'])
        ->firstOrFail();

    // El resto del método permanece igual...
    $verifyUrl = route('store.verify', [
        'tenant' => $tenant->slug,
        'code' => $order->code,
    ]);

        // Generar QR
        $qrCode = null;
        if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            $qrCode = base64_encode(
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(250)
                    ->margin(1)
                    ->generate($verifyUrl)
            );
        }

        return view('store.checkout-confirmation', [
            'tenant' => $tenant,
            'order' => $order,
            'items' => $order->items,
            'rifa' => $order->rifa,
            'paymentAccount' => $order->paymentAccount,
            'verifyUrl' => $verifyUrl,
            'qrCode' => $qrCode,
        ]);
    }

    /** Recibe confirmación de pago (desde el checkout) */
public function pay(Tenant $tenant, string $code, Request $request)
{
    $order = Order::where('tenant_id', $tenant->id)->where('code', $code)->firstOrFail();

    // URL de confirmación (la usaremos varias veces)
    $confirmUrl = route('store.checkout.confirmation', [
        'tenant' => $tenant->slug ?? $tenant->id,
        'code'   => $order->code,
    ]);

    // === Idempotencia: si ya está Paid, trata como éxito y redirige a confirmación
    if ($order->status === OrderStatus::Paid) {
        return $request->expectsJson()
            ? response()->json(['ok' => true, 'redirect' => $confirmUrl])
            : redirect()->to($confirmUrl);
    }

    // === Idempotencia: si ya está Submitted, trata como éxito y redirige a confirmación
    if ($order->status === OrderStatus::Submitted) {
        return $request->expectsJson()
            ? response()->json([
                'ok'       => true,
                'message'  => 'Pago ya enviado. Te llevamos a la confirmación.',
                'redirect' => $confirmUrl,
            ])
            : redirect()->to($confirmUrl);
    }

    // Reserva expirada
    if ($order->status === OrderStatus::Pending && $order->expires_at && $order->expires_at->isPast()) {
        return $request->expectsJson()
            ? response()->json(['ok' => false, 'message' => 'La reserva ya venció.'], 422)
            : back()->withErrors(['referencia' => 'La reserva ya venció.']);
    }

    // Validación de inputs - ACTUALIZADA con campos obligatorios del cliente
    Validator::validate($request->all(), [
        'payment_account_id' => ['required'],
        'referencia'         => ['required', 'string', 'max:64'],
        'voucher'            => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        'customer_email'     => ['required', 'email', 'max:255'],
        'customer_name'      => ['required', 'string', 'max:255'],
        'customer_whatsapp'  => ['required', 'string', 'max:50'],
    ]);

    // Actualiza estatus y guarda campos
    $order->status = OrderStatus::Submitted;
    $order->payment_account_id = $request->input('payment_account_id');
    $order->referencia = $request->input('referencia');

    // SOLUCIÓN: Guardar datos del cliente que vienen del formulario
    if ($request->filled('customer_email')) {
        $order->customer_email = $request->input('customer_email');
    }
    if ($request->filled('customer_name')) {
        $order->customer_name = $request->input('customer_name');
    }
    if ($request->filled('customer_whatsapp')) {
        $order->customer_phone = $request->input('customer_whatsapp'); // Se guarda como customer_phone
    }

    // Log para debug (opcional, puedes removerlo después de verificar que funciona)
    \Log::info('Datos del cliente guardados en pay()', [
        'order_id' => $order->id,
        'email' => $order->customer_email,
        'name' => $order->customer_name,
        'phone' => $order->customer_phone
    ]);

    if ($request->hasFile('voucher')) {
        $path = $request->file('voucher')->store("vouchers/{$order->id}", 'public');
        $order->voucher_path = $path;
    }

    $order->save();

 // Correos "pago enviado"
try {
    if ($order->customer_email && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
        // Cliente recibe su correo (vista: emails/payment-submitted.blade.php)
        Mail::to($order->customer_email)->queue(new \App\Mail\PaymentSubmittedMail($order, 'customer'));
        \Log::info('Email encolado para el cliente', ['email' => $order->customer_email]);
    }
} catch (\Throwable $e) { 
    \Log::error('Error enviando email al cliente', ['error' => $e->getMessage()]);
    report($e); 
}

try {
    $adminEmail = $order->tenant->notify_email
        ?? config('mail.admin_address')
        ?? config('mail.from.address');

    if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        // Admin recibe su correo (vista: emails/orders/payment-submitted-admin.blade.php)
        // y con Reply-To apuntando al cliente
        Mail::to($adminEmail)->queue(new \App\Mail\PaymentSubmittedMail($order, 'admin'));
        \Log::info('Email encolado para el admin', ['email' => $adminEmail]);
    }
} catch (\Throwable $e) { 
    \Log::error('Error enviando email al admin', ['error' => $e->getMessage()]);
    report($e); 
}


    // ==========================

    // Respuesta según tipo de request
    if ($request->expectsJson()) {
        return response()->json([
            'ok'       => true,
            'message'  => 'Pago enviado, será verificado.',
            'redirect' => $confirmUrl,  // para que el front redirija sin más
        ]);
    }

    return redirect()->to($confirmUrl);
}

}