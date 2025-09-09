@extends('emails.layouts.base')

@section('content')
@php
  $primary   = '#2563eb';
  $tenant    = $order->tenant;
  $rifa      = $order->rifa;
  $tickets   = $order->items?->pluck('numero')->sort();
  $monto     = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
  $verifyUrl = url("/t/".($tenant->slug ?? $tenant->id)."/verify?code=".$order->code);
@endphp

{{-- Badge de estado --}}
<div style="text-align:center;margin-bottom:14px;">
  <span class="pill" style="background:#e0ffe5;color:#16a34a;">
    Pagado
  </span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    Pago verificado — ¡Participación confirmada!
  </h2>
  <div class="p muted" style="font-size:14px;">
    Tu pago fue verificado y tus boletos quedaron confirmados.
  </div>
</div>

{{-- Tabla de detalles --}}
<table class="table" style="margin:12px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:{{ $primary }}08;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#111827;">
        <tr>
          <td style="padding:6px 0;width:34%;">Código:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $order->code }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Rifa:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $rifa->nombre ?? $rifa->titulo ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Boletos:</td>
          <td style="padding:6px 0;">
            @if($tickets && count($tickets))
              @foreach($tickets as $num)
                <span class="pill" style="border:1.2px dashed {{ $primary }};background:#fff;color:{{ $primary }};">
                  #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
                </span>
              @endforeach
            @else
              -
            @endif
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Total:</td>
          <td style="padding:6px 0;font-weight:700;">${{ $monto }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Estado:</td>
          <td style="padding:6px 0;">
            <span class="pill" style="background:#e0ffe5;color:#16a34a;">
              Pagado
            </span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{{-- Botón principal --}}
<div style="text-align:center;margin-top:20px;">
  <a href="{{ $verifyUrl }}" class="btn" style="background:{{ $primary }};">Ver mis boletos</a>
  <div class="small" style="margin-top:8px;">
    Si el botón no funciona, copia y pega:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection
