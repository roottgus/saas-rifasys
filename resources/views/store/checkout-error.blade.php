@extends('layouts.store')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <div class="bg-white shadow-xl rounded-3xl px-8 py-10 max-w-lg w-full text-center">
        {{-- ANIMACIÓN WOW --}}
        <div class="flex justify-center mb-6">
            {{-- Puedes usar Lottie animación, GIF, SVG, o FontAwesome, etc. --}}
            <img src="https://assets10.lottiefiles.com/private_files/lf30_u8o7BL.json" alt="Expirado" width="120" class="mx-auto" id="lottie-error" />
            {{-- O un SVG fallback si no tienes Lottie --}}
            {{-- <svg class="w-20 h-20 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="red" stroke-width="3"/><path stroke="red" stroke-width="3" d="M8 8l8 8M16 8l-8 8"/></svg> --}}
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $title ?? '¡Ups! Algo salió mal.' }}</h2>
        <p class="text-gray-600 mb-8 text-lg">{{ $description ?? 'El enlace ya expiró o la orden no existe.' }}</p>
        <a href="{{ route('store.home', $tenant) }}"
           class="inline-block bg-[var(--primary,#fc0d1b)] hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-full shadow transition">Ir a la tienda</a>
    </div>
</div>
{{-- Lottie Animation Loader --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
    // Cambia por el link de la animación que quieras (este es demo)
    const lottieError = document.getElementById('lottie-error');
    if (lottieError) {
        const player = document.createElement('lottie-player');
        player.setAttribute('src', 'https://lottie.host/da1cfd23-0551-4410-9778-93a937fc3566/WwTkvQsm0A.json'); // Animación X roja
        player.setAttribute('background', 'transparent');
        player.setAttribute('speed', '1');
        player.setAttribute('style', 'width: 120px; height: 120px;');
        player.setAttribute('autoplay', '');
        lottieError.replaceWith(player);
    }
</script>
@endsection
