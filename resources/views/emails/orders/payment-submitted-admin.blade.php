@extends('emails.layouts.base')

@section('content')
@php
    $primary   = '#2563eb';

    $rifa      = $order->rifa ?? null;
    $tickets   = $order->items?->pluck('numero')->filter()->sort() ?? collect();
    $total     = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
    $voucher   = $order->voucher_path ?? null;
    $payment   = $order->paymentAccount ?? null;

    $voucherUrl = $voucher ? url(\Illuminate\Support\Facades\Storage::url($voucher)) : null;
    $verifyUrl = $verifyUrl ?? (url('/panel/orders/' . ($order->id ?? '')));
@endphp

<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fef9c3;color:#b45309;">
    Pago pendiente de verificación
  </span>
</div>

<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    Comprobante de pago enviado
  </h2>
  <div class="p muted" style="font-size:14px;">
    El usuario <b>{{ $order->customer_name ?? '-' }}</b> ha enviado un pago que requiere tu verificación.
  </div>
</div>

<table class="table" style="margin:12px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#fef9c3;">
      <table width="100%" style="font-size:14px;color:#111827;">
        <tr>
          <td style="width:34%;">Código orden:</td>
          <td style="font-weight:700;">{{ $order->code }}</td>
        </tr>
        <tr>
          <td>Cliente:</td>
          <td style="font-weight:700;">
            {{ $order->customer_name ?? '-' }}<br>
            <span class="small" style="color:#64748b;">
              {{ $order->customer_email ?? '-' }} / {{ $order->customer_phone ?? '-' }}
            </span>
          </td>
        </tr>
        <tr>
          <td>Rifa:</td>
          <td style="font-weight:700;">{{ $rifa->titulo ?? $rifa->nombre ?? '-' }}</td>
        </tr>
        <tr>
          <td>Números:</td>
          <td>
            @if($tickets->count())
              @foreach($tickets as $num)
                <span class="pill" style="border:1.2px dashed #2563eb;background:#fff;color:#2563eb;">
                  #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
                </span>
              @endforeach
            @else
              —
            @endif
          </td>
        </tr>
        <tr>
          <td>Total:</td>
          <td style="font-weight:700;">${{ $total }}</td>
        </tr>
        @if($payment)
        <tr>
          <td>Método pago:</td>
          <td>
            {{ $payment->etiqueta ?? ucfirst($payment->tipo) ?? '-' }}
            @if($payment->banco)
              <span class="small">({{ $payment->banco }})</span>
            @endif
          </td>
        </tr>
        @endif
        @if($order->referencia)
        <tr>
          <td>Referencia:</td>
          <td style="font-family:monospace;">{{ $order->referencia }}</td>
        </tr>
        @endif
      </table>
    </td>
  </tr>
</table>

@if($voucherUrl)
  <div style="text-align:center;margin:20px 0 0;">
    <div class="small" style="margin-bottom:6px;color:#b45309;">Comprobante adjunto:</div>
    <img src="{{ $voucherUrl }}"
         alt="Comprobante de pago"
         style="width:180px;max-width:90vw;border-radius:12px;box-shadow:0 1px 8px #0001;display:block;margin:0 auto;">
  </div>
@endif

<div style="text-align:center;margin-top:20px;">
  <a href="{{ $verifyUrl }}" class="btn" style="background:#2563eb;">
    Revisar orden en sistema
  </a>
  <div class="small" style="margin-top:8px;">
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection
