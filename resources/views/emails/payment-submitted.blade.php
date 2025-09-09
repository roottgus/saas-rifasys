@extends('emails.layouts.base')

@section('content')
@php
  $rifa    = $order->rifa;
  $items   = $order->items;
  $total   = number_format((float)$order->total_amount, 2, '.', ',');
  $venc    = $order->expires_at ? $order->expires_at->format('d/m/Y H:i') : null;
  $primary = '#2563eb';
@endphp

<div class="p muted" style="color:#64748b;font-size:15px;text-align:center;margin-bottom:20px;">
  Gracias, hemos recibido tu comprobante para la orden
  <span style="font-family:monospace;font-weight:700;color:#222;">{{ $order->code }}</span>.<br>
  Tu pago está <b>en verificación</b>. Te notificaremos cuando sea confirmado.
</div>

{{-- Detalles de la compra --}}
<table class="table" style="margin:18px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#f0f4ff;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
        <tr>
          <td style="color:#5b6478;">Rifa:</td>
          <td style="text-align:right;color:#151b29;font-weight:700;">
            {{ $rifa->nombre ?? $rifa->titulo ?? '-' }}
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding-top:7px;">Boletos:</td>
          <td style="text-align:right;padding-top:7px;">
            @foreach($items->pluck('numero')->sort() as $num)
              <span class="pill" style="border:1.2px dashed {{ $primary }};background:#fff;color:{{ $primary }};">
                #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
              </span>
            @endforeach
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding-top:7px;">Total:</td>
          <td style="text-align:right;font-weight:800;color:{{ $primary }};padding-top:7px;">
            ${{ $total }}
          </td>
        </tr>
        @if($order->paymentAccount)
        <tr>
          <td style="color:#5b6478;padding-top:7px;">Método de pago:</td>
          <td style="text-align:right;padding-top:7px;">
            {{ $order->paymentAccount->etiqueta ?? ucfirst($order->paymentAccount->tipo) ?? 'No especificado' }}
            @if($order->paymentAccount->banco)
              <span class="small">({{ $order->paymentAccount->banco }})</span>
            @endif
          </td>
        </tr>
        @endif
        @if($order->referencia)
        <tr>
          <td style="color:#5b6478;padding-top:7px;">Referencia:</td>
          <td style="text-align:right;padding-top:7px;font-family:monospace;">{{ $order->referencia }}</td>
        </tr>
        @endif
        @if($venc)
        <tr>
          <td style="color:#5b6478;padding-top:7px;">Reserva activa hasta:</td>
          <td style="text-align:right;padding-top:7px;">{{ $venc }}</td>
        </tr>
        @endif
      </table>
    </td>
  </tr>
</table>

{{-- Botón principal --}}
<div style="text-align:center;margin-top:20px;">
  <a href="{{ $verifyUrl }}"
     style="display:inline-block;background:{{ $primary }};color:#fff !important;text-decoration:none;font-weight:800;padding:11px 18px;border-radius:10px;font-size:16px;line-height:1.2;border:0;"
     target="_blank">
    Ver estado de mi orden
  </a>
  <div class="small" style="margin-top:8px;">
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>

<p class="small" style="margin:18px 0 0;text-align:center;">
  Si tu pago es rechazado o requiere información adicional, te contactaremos por email o WhatsApp.<br>
  Este mensaje es automático, no respondas a este correo.
</p>
@endsection
