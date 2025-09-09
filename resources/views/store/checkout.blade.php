{{-- resources/views/store/checkout.blade.php --}}
@extends('layouts.store')

@section('content')
    @php
        use App\Enums\OrderStatus;
        $isExpired = isset($expired) && $expired;
    @endphp

    <div class="max-w-3xl mx-auto w-full px-4 py-8">
        {{-- Cabecera de la orden --}}
        <div class="mb-7 flex flex-col items-center gap-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[var(--primary)] opacity-90">
                    <i class="fas fa-ticket-alt mr-1"></i> Código de orden
                </span>
            </div>
            <div class="font-mono text-2xl md:text-3xl font-black text-gray-900 tracking-[0.08em] bg-[var(--primary)]/80 px-4 py-2 rounded-2xl shadow border-4 border-white mb-1">
                {{ $order->code }}
            </div>

            @if($order->expires_at)
                <div id="reservation-timer"
                     class="text-xs font-bold text-[var(--primary)] bg-[var(--primary)]/10 px-3 py-1 rounded-full shadow-inner mb-2 text-center border border-[var(--primary)]"
                     style="letter-spacing: 0.03em;">
                    <!-- El timer aparecerá aquí -->
                </div>
                <script>
                  (() => {
                    // Formato ISO (Laravel → JS)
                    const expiresAt = "{{ $order->expires_at->format('Y-m-d\TH:i:sP') }}";
                    const timerDiv = document.getElementById('reservation-timer');

                    function updateTimer() {
                      const expireDate = new Date(expiresAt);
                      const now = new Date();
                      let diff = Math.floor((expireDate - now) / 1000);

                      if (diff <= 0) {
                        timerDiv.textContent = "⏰ ¡Reserva expirada!";
                        timerDiv.style.color = "#b91c1c";
                        timerDiv.style.background = "#fee2e2";
                        timerDiv.style.borderColor = "#b91c1c";
                        return;
                      }
                      const h = String(Math.floor(diff/3600)).padStart(2,'0');
                      const m = String(Math.floor((diff%3600)/60)).padStart(2,'0');
                      const s = String(diff%60).padStart(2,'0');
                      timerDiv.textContent = `⏰ Tiempo restante para pagar: ${h}:${m}:${s}`;
                    }
                    updateTimer();
                    setInterval(updateTimer, 1000);
                  })();
                </script>
            @endif

        </div>

        {{-- Sección dinámica --}}
        @if ($isExpired)
            @include('store.partials.checkout-expired', ['order' => $order, 'tenant' => $tenant])

        @elseif ($order->status === OrderStatus::Paid)
            @include('store.partials.checkout-success', ['order' => $order, 'tenant' => $tenant])

        @elseif ($order->status === OrderStatus::Pending)
            {{-- ==== CAMBIO CLAVE: NO ENVIAR accountsForJs ==== --}}
            @include('store.partials.rifa-checkout', [
                'order'             => $order,
                'tenant'            => $tenant,
                'paymentAccounts'   => $paymentAccounts ?? collect(),
                'tSlug'             => $tenant->slug,
                'tasaBs'            => $tenant->tasa_bs,
                // 'accountsForJs'   => $accountsForJs ?? [],  <-- QUITADO
                'selectedAccountId' => $selectedAccountId ?? null,
            ])

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const box = document.getElementById('inlineCheckout');
                    if (box) box.classList.remove('hidden');
                    try { window.updateCheckoutSummary?.(); } catch {}
                    box?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            </script>

        @elseif ($order->status === OrderStatus::Cancelled)
            @include('store.partials.checkout-cancelled', ['order' => $order, 'tenant' => $tenant])

        @else
            @include('store.partials.checkout-error', ['order' => $order, 'tenant' => $tenant])
        @endif
    </div>
@endsection
