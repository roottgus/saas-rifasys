@extends('layouts.store')

@php
    /** @var \App\Models\Rifa $rifa */

    // Tenant desde la ruta o la rifa
    $tenantFromRoute = request()->route('tenant');
    $tenantView = $tenantFromRoute instanceof \App\Models\Tenant
        ? $tenantFromRoute
        : ($rifa->tenant ?? null);

    $tenantSlug = $tenantView?->slug;
    $tenantId   = $tenantView?->id;

    // Parametro robusto para las rutas: slug si existe, si no id
    $tenantParam = $tenantSlug ?: $tenantId;

    // Parámetros de la rifa
    $total     = (int) ($rifa->total_numeros ?? 0);
    $paid      = (int) $rifa->numeros()->where('estado','pagado')->count();
    $reserved  = (int) $rifa->numeros()->where('estado','reservado')->count();
    $available = max($total - $paid - $reserved, 0);
    $percent   = $total > 0 ? (int) round(($paid / $total) * 100) : 0;

    $minSel = (int) ($rifa->min_por_compra ?? 1);
    $maxSel = (int) ($rifa->max_por_compra ?? 9999);
    $price  = (float) ($rifa->precio ?? 0);

    // URL del endpoint de reserva — usa slug de la rifa explícitamente
    $reserveUrl = $tenantParam
        ? route('store.reserve', ['tenant' => $tenantParam, 'rifa' => $rifa->slug ?? $rifa])
        : null;

    $minutes = (int) ($rifa->reserva_minutos ?? 240);

    $nums    = $rifa->numeros()->orderBy('numero')->get(['numero','estado']);
    $premios = $rifa->specialPrizes()
        ->orderBy('draw_at')->orderBy('id')
        ->get(['title','lottery_name','lottery_type','draw_at']);

    $paymentAccounts = $paymentAccounts ?? collect();

    // Fondo body
    $bgColor = $rifa->bg_color ?? null;
    $bgImage = $rifa->bg_image_path ? \Illuminate\Support\Facades\Storage::url($rifa->bg_image_path) : null;

    // Boot para JS
    $storeBoot = [
        'Rifa' => [
            'price'    => $price,
            'min'      => $minSel,
            'max'      => $maxSel,
            'postUrl'  => $reserveUrl,     // <--- IMPORTANTE
            'minutes'  => $minutes,
            'pageSize' => 200,
        ],
        'PreAnim' => [
            'gif' => asset('images/ticket-preloader.gif'),
        ],
    ];
@endphp

@push('body-bg')
    <style>
        body {
            @if($bgImage)
                background-image: url('{{ $bgImage }}');
                background-size: 340px 340px;
                background-repeat: repeat;
                background-position: top center;
                background-color: #fff;
            @elseif($bgColor)
                background: {{ $bgColor }};
            @else
                background: #f7f8fa;
            @endif
        }
    </style>
@endpush

@section('content')
<script>
  window.Store = Object.assign(window.Store || {}, @json($storeBoot));
</script>

<div class="w-full max-w-[1150px] mx-auto px-2 sm:px-4 md:px-8 py-5">
  <div class="bg-white/90 shadow-xl rounded-3xl border border-white/70 backdrop-blur-[2px] px-2 sm:px-8 py-6 sm:py-10 min-h-[82vh]">
    @include('store.partials.rifa-hero',     compact('rifa','percent','paid','available','minSel','maxSel','price','premios'))
    @include('store.partials.rifa-stats',    compact('total','percent'))

    @include('store.partials.rifa-checkout', [
    'order' => $order ?? null,
    'tenant' => $tenantView,
    'paymentAccounts' => $paymentAccounts,
    'tasaBs' => $tasaBs ?? null,
    'tSlug' => $tenantSlug,
])
    
  </div>
</div>
@endsection

@push('modals')
  @include('store.partials.rifa-numbers', ['nums' => $nums, 'reserveUrl' => $reserveUrl, 'maxSel' => $maxSel])
  @include('store.partials.modal-reserva-pago')
  @includeIf('store.partials.rifa-preload')
@endpush

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (window.initRifaPage) window.initRifaPage();
  });
</script>