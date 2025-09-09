@extends('layouts.store')

@section('content')
@php
    /** @var \Illuminate\Support\Collection|\App\Models\Rifa[] $rifas */
@endphp

<div class="flex items-end justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Rifas disponibles</h1>
    <p class="text-sm opacity-70">Elige tu rifa favorita y compra tus números en segundos.</p>
  </div>
  <form method="GET" class="hidden md:block">
    <input class="input w-64" type="search" name="q" placeholder="Buscar rifa…" value="{{ request('q') }}">
  </form>
</div>

@if($rifas->isEmpty())
  <div class="card text-center py-16">
    <div class="text-5xl mb-3">🎟️</div>
    <h2 class="text-xl font-semibold">No hay rifas activas</h2>
    <p class="opacity-70">Vuelve pronto: estamos preparando nuevas sorpresas.</p>
  </div>
@else
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($rifas as $r)
      <a href="{{ route('store.rifa', ['tenant'=>$tenant->slug,'rifa'=>$r->slug]) }}" class="group card overflow-hidden p-0 hover:shadow-xl transition">
        <div class="relative aspect-[16/9] bg-neutral-100">
          @if($r->banner_path)
            <img src="{{ Storage::url($r->banner_path) }}" class="w-full h-full object-cover" alt="{{ $r->titulo }}">
          @endif
          @if($r->estado === 'activa')
            <span class="absolute left-3 top-3 chip !bg-green-600/90 !text-white">Activa</span>
          @else
            <span class="absolute left-3 top-3 chip !bg-gray-600/80 !text-white capitalize">{{ $r->estado }}</span>
          @endif
        </div>

        <div class="p-4">
          <h3 class="font-bold text-lg leading-snug group-hover:underline">{{ $r->titulo }}</h3>
          <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
            <span class="chip">Precio: <strong>${{ number_format($r->precio,2) }}</strong></span>
            @if($r->lottery_name)
              <span class="chip">{{ $r->lottery_name }} @if($r->lottery_type) · {{ $r->lottery_type }} @endif</span>
            @endif
          </div>

          @php
            $total=(int)$r->total_numeros;
            $paid=(int)$r->numeros()->where('estado','pagado')->count();
            $reserved=(int)$r->numeros()->where('estado','reservado')->count();
            $available=max($total-$paid-$reserved,0);
            $pct=$total>0? (int) round(($paid/$total)*100):0;
          @endphp
          <div class="mt-3">
            <div class="h-2 w-full rounded-full bg-black/10 overflow-hidden">
  <div class="h-full bg-[var(--primary)] transition-all" style="width: {{ $pct }}%"></div>
</div>

            <div class="mt-1 text-xs opacity-70">{{ $paid }} vendidos · {{ $available }} disponibles</div>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <span class="text-sm opacity-70">Mín {{ $r->min_por_compra }} · Máx {{ $r->max_por_compra }}</span>
            <span class="btn btn-primary">Ver rifa</span>
          </div>
        </div>
      </a>
    @endforeach
  </div>
@endif
@endsection
