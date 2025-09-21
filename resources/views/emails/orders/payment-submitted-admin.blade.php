@extends('emails.layouts.base')

@section('content')
@php
  // Color azul fijo
  $primary = '#2563eb';

  // Variables
  $rifa      = $order->rifa;  // 🔴 AGREGAR ESTA LÍNEA
  $tickets   = $order->items?->pluck('numero')->sort();
  $total     = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
  $voucher   = $order->voucher_path ?? null;
  $payment   = $order->paymentAccount ?? null;
@endphp

{{-- Badge de estado --}}
<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fef9c3;color:#b45309;">
    Pago pendiente de verificación
  </span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    Un usuario envió comprobante de pago
  </h2>
  <div class="p muted" style="font-size:14px;">
    El usuario <b>{{ $order->customer_name ?? '-' }}</b> envió un pago que requiere verificación.<br>
    Por favor, revisa y aprueba o rechaza la orden en el sistema.
  </div>
</div>

{{-- Tabla de detalles --}}
<table class="table" style="margin:12px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#fef9c3;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#111827;">
        <tr>
          <td style="padding:6px 0;width:34%;">Código orden:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $order->code }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Cliente:</td>
          <td style="padding:6px 0;font-weight:700;">
            {{ $order->customer_name ?? '-' }}<br>
            <span class="small" style="color:#64748b;">
              Email: {{ $order->customer_email ?? '-' }}<br>
              Teléfono: {{ $order->customer_phone ?? '-' }}
            </span>
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Rifa:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $rifa->titulo ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Números:</td>
          <td style="padding:6px 0;">
            @foreach($tickets as $num)
              <span class="pill" style="border:1.2px dashed #2563eb;background:#fff;color:#2563eb;">
                #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
              </span>
            @endforeach
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Total:</td>
          <td style="padding:6px 0;font-weight:700;">${{ $total }}</td>
        </tr>
        @if($payment)
        <tr>
          <td style="padding:6px 0;">Método pago:</td>
          <td style="padding:6px 0;">
            {{ $payment->etiqueta ?? ucfirst($payment->tipo) ?? '-' }}
            @if($payment->banco)
              <span class="small">({{ $payment->banco }})</span>
            @endif
          </td>
        </tr>
        @endif
        @if($order->referencia)
        <tr>
          <td style="padding:6px 0;">Referencia:</td>
          <td style="padding:6px 0;font-family:monospace;">{{ $order->referencia }}</td>
        </tr>
        @endif
      </table>
    </td>
  </tr>
</table>

{{-- Voucher adjunto (si existe) --}}
@if($voucher)
  <div style="text-align:center;margin:20px 0 0;">
    <div class="small" style="margin-bottom:6px;color:#b45309;">Comprobante enviado:</div>
    <img src="{{ \Illuminate\Support\Facades\Storage::url($voucher) }}"
         alt="Comprobante de pago"
         style="width:180px;max-width:90vw;border-radius:12px;box-shadow:0 1px 8px #0001;display:block;margin:0 auto;">
  </div>
@endif

{{-- Botón para ver orden en sistema --}}
<div style="text-align:center;margin-top:20px;">
  <a href="{{ $verifyUrl }}" class="btn" style="background:#2563eb;">
    Revisar orden en sistema
  </a>
  <div class="small" style="margin-top:8px;">
    Puedes gestionar esta orden desde el panel de administración.<br>
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection