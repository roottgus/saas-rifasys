@extends('layouts.store')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <div class="bg-white shadow-xl rounded-3xl px-8 py-10 max-w-lg w-full text-center">
        {{-- ANIMACIÓN WOW --}}
        <div class="flex justify-center mb-6">
            <img src="https://assets1.lottiefiles.com/packages/lf20_myejiggj.json" alt="Pago Exitoso" width="120" class="mx-auto" id="lottie-success" />
        </div>
        <h2 class="text-2xl font-bold text-green-600 mb-3">¡Pago recibido!</h2>
        <p class="text-gray-700 mb-5 text-lg">
            Tus boletos han sido reservados.<br>
            <span class="font-semibold">En breve recibirás un correo con tus números y detalles de la compra.</span>
            <br>
            Si no ves el email, revisa tu carpeta de spam/promociones.
        </p>
        <a href="{{ route('store.home', $tenant) }}"
           class="inline-block bg-[var(--primary,#28d17b)] hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-full shadow transition">Ir a inicio</a>
    </div>
</div>
{{-- Lottie Animation Loader --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
    const lottieSuccess = document.getElementById('lottie-success');
    if (lottieSuccess) {
        const player = document.createElement('lottie-player');
        player.setAttribute('src', 'https://lottie.host/2c3de7c8-0d31-4e09-aac0-c4debe9874d6/tVABw2Ky2M.json'); // Check verde animado wow
        player.setAttribute('background', 'transparent');
        player.setAttribute('speed', '1');
        player.setAttribute('style', 'width: 120px; height: 120px;');
        player.setAttribute('autoplay', '');
        lottieSuccess.replaceWith(player);
    }
</script>
@endsection
