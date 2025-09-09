@extends('emails.layouts.base')

@section('content')
@php
  // Color azul fijo
  $primary = '#2563eb';

  // Tickets y totales
  $tickets = $order->items?->pluck('numero')->sort();
  $total   = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
@endphp

{{-- Badge de estado --}}
<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fef9c3;color:#b45309;">Nueva reserva</span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    Un usuario realizó una reserva
  </h2>
  <div class="p muted" style="font-size:14px;">
    El usuario <b>{{ $customerName ?? $order->customer_name ?? '-' }}</b> acaba de reservar números en la rifa <b>{{ $rifa->titulo ?? '-' }}</b>.
  </div>
</div>

{{-- Tabla de detalles --}}
<table class="table" style="margin:12px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#2563eb08;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#111827;">
        <tr>
          <td style="padding:6px 0;width:34%;">Código reserva:</td>
          <td style="padding:6px 0;font-weight:700;">{{ $order->code }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Cliente:</td>
          <td style="padding:6px 0;font-weight:700;">
            {{ $customerName ?? $order->customer_name ?? '-' }}<br>
            <span class="small" style="color:#64748b;">
              Email: {{ $order->customer_email ?? '-' }}<br>
              Teléfono: {{ $order->customer_phone ?? '-' }}
            </span>
          </td>
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
        <tr>
          <td style="padding:6px 0;">Vence:</td>
          <td style="padding:6px 0;font-weight:700;color:#b91c1c">
            {{ $reservedUntil ?? ($order->expires_at ? $order->expires_at->format('d/m/Y H:i') : '-') }}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{{-- Botón para ver reserva --}}
<div style="text-align:center;margin-top:18px;">
  <a href="{{ $verifyUrl }}" class="btn" style="background:#2563eb;">
    Ver reserva en sistema
  </a>
  <div class="small" style="margin-top:8px;">
    Puedes gestionar la reserva desde el panel de administración.<br>
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection
