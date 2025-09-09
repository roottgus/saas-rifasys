@extends('layouts.store')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-10">
    <div id="reserva-confirmada-wrapper"
         class="bg-white shadow-2xl rounded-2xl p-5 max-w-md w-full border-2 relative hidden"
         style="border-color: var(--primary);">

        <div class="flex justify-center -mt-12 mb-2">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png"
                class="w-16 h-16 rounded-full border-4 border-white bg-white drop-shadow-xl"
                alt="Éxito" />
        </div>

        <div class="text-2xl font-extrabold text-center text-[var(--primary)] mb-2">¡Reserva confirmada!</div>
        <div class="text-xs uppercase font-bold text-gray-400 tracking-widest mb-2 text-center">
            Código: <span class="font-mono text-sm text-black">{{ $order->code }}</span>
        </div>
        <div class="mt-2 text-left">
            <div class="font-semibold text-gray-700">Números reservados</div>
            <div class="mt-1 flex flex-wrap gap-2 text-center justify-start text-base font-bold text-[var(--primary)]">
                @foreach($order->items as $item)
                    <span class="inline-block bg-[var(--primary)]/10 text-[var(--primary)] rounded-lg px-2 py-1 shadow-sm border border-[var(--primary)]/20 min-w-[38px]">{{ $item->numero }}</span>
                @endforeach
            </div>
            @if($order->expires_at)
    <div id="reservation-timer"
         class="mt-4 text-sm font-bold text-[var(--primary)] bg-[var(--primary)]/10 border border-[var(--primary)] rounded-full px-3 py-1 text-center shadow-inner"
         style="letter-spacing:0.03em;">
        <!-- El timer aparecerá aquí -->
    </div>
    <script>
      (() => {
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
          timerDiv.textContent = `⏰ Tiempo restante: ${h}:${m}:${s}`;
        }
        updateTimer();
        setInterval(updateTimer, 1000);
      })();
    </script>
@endif

        </div>
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('store.checkout', ['tenant' => $tenant->slug, 'code' => $order->code]) }}"
                class="px-5 py-3 rounded-xl bg-[var(--primary)] hover:bg-[var(--primary-dark)] text-white font-bold shadow transition">
                <svg class="inline mr-2 mb-1" width="22" height="22" fill="none" stroke="currentColor"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                Pagar ahora
            </a>
            <a href="{{ route('store.rifa', ['tenant' => $tenant->slug, 'rifa' => $order->rifa->slug]) }}"

                class="px-5 py-3 rounded-xl border border-gray-300 bg-white font-semibold text-gray-700 hover:bg-gray-100 shadow">
                <svg class="inline mr-2 mb-1" width="22" height="22" fill="none" stroke="currentColor"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Volver a la rifa
            </a>
        </div>
        <div class="mt-5 text-xs text-gray-500 text-center italic">
            ¡Asegura tu participación! Mientras más boletos, más oportunidades de ganar.<br>
            <span class="font-bold text-[var(--primary)]">¡Suerte!</span>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        title: '¡Reserva confirmada!',
        text: 'Tus boletos fueron reservados con éxito.',
        icon: 'success',
        showConfirmButton: false,
        timer: 1700,
        background: '#f0fdf4',
        color: '#166534',
        customClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        didOpen: function() {
            setTimeout(function() {
                Swal.close();
                document.getElementById('reserva-confirmada-wrapper').classList.remove('hidden');
            }, 1200);
        }
    });
});
</script>
@endpush
