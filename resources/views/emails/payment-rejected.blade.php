@extends('emails.layouts.base')

@section('content')
@php
  $rifa    = $rifa ?? $order->rifa;
  $tickets = $order->items?->pluck('numero')->sort();
  $total   = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
  $primary = '#b91c1c'; // rojo fuerte fijo
@endphp

{{-- Badge de estado (rojo fuerte) --}}
<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fee2e2;color:#b91c1c;">Pago rechazado</span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    Tu pago fue rechazado
  </h2>
  <div class="p muted" style="font-size:14px;">
    Hola {{ $customerName ?? $order->customer_name ?? 'Cliente' }}, tu comprobante no pudo ser validado.<br>
    @if(!empty($rejectReason))
      <span style="color:#b91c1c;">Motivo: {{ $rejectReason }}</span><br>
    @endif
    Por favor, revisa la información y vuelve a enviar tu comprobante correctamente o utiliza otro método de pago.
  </div>
</div>

{{-- Tabla de detalles --}}
<table class="table" style="margin:12px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#fee2e2;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#111827;">
        <tr>
          <td style="padding:6px 0;width:34%;">Código:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $order->code }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Rifa:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $rifa->titulo ?? $rifa->nombre ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Números:</td>
          <td style="padding:6px 0;">
            @foreach($tickets as $num)
              <span class="pill" style="border:1.2px dashed #b91c1c;background:#fff;color:#b91c1c;">
                #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
              </span>
            @endforeach
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Total:</td>
          <td style="padding:6px 0;font-weight:700;">${{ $total }}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{{-- Botón para reenviar pago --}}
<div style="text-align:center;margin-top:18px;">
  <a href="{{ $verifyUrl }}" class="btn" style="background:#b91c1c;">
    Reenviar comprobante
  </a>
  <div class="small" style="margin-top:8px;">
    Si necesitas ayuda, responde a este correo o contáctanos por WhatsApp.<br>
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection
