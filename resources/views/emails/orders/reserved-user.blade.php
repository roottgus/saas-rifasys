@extends('emails.layouts.base')

@section('content')
@php
  $primary = '#2563eb';
  $rifaTitle = $rifa->titulo ?? $rifa->nombre ?? '-';
  $tickets = $order->items?->pluck('numero')->sort();
  $total = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
@endphp

{{-- Badge de estado --}}
<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fef9c3;color:#b45309;">
    Reserva creada
  </span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $primary }};">
    ¡Tu reserva está activa!
  </h2>
  <div class="p muted" style="font-size:14px;">
    Hola {{ $customerName ?? $order->customer_name ?? 'Cliente' }}, tus números han sido reservados.
  </div>
</div>

{{-- Tabla de detalles --}}
<table class="table" style="margin:18px auto 0 auto;">
  <tr>
    <td class="cell" style="padding:16px 20px;background:#f0f4ff;">
      <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
        <tr>
          <td style="color:#5b6478;padding:6px 0;width:32%;">Código:</td>
          <td style="padding:6px 0;text-align:right;font-weight:700;">
            {{ $order->code }}
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding:6px 0;">Rifa:</td>
          <td style="padding:6px 0;text-align:right;font-weight:700;">
            <span style="display:inline-block;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;vertical-align:middle;">
              {{ $order->rifa->titulo ?? $order->rifa->nombre ?? '-' }}
            </span>
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding:6px 0;">Números:</td>
          <td style="padding:6px 0;text-align:right;">
            @foreach($tickets as $num)
              <span class="pill" style="border:1.2px dashed {{ $primary }};background:#fff;color:{{ $primary }};">
                #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
              </span>
            @endforeach
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding:6px 0;">Total:</td>
          <td style="padding:6px 0;text-align:right;font-weight:800;color:{{ $primary }};">
            ${{ $total }}
          </td>
        </tr>
        <tr>
          <td style="color:#5b6478;padding:6px 0;">Vigencia:</td>
          <td style="padding:6px 0;text-align:right;color:#b91c1c;font-weight:700;">
            4 horas a partir de tu reserva
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>


{{-- Botón principal --}}
<div style="text-align:center;margin-top:20px;">
  <a href="{{ $checkoutUrl }}"
   style="display:inline-block;background:{{ $primary }};color:#fff !important;text-decoration:none;font-weight:800;padding:11px 18px;border-radius:10px;font-size:16px;line-height:1.2;border:0;"
   target="_blank">
   Ir a pagar ahora
</a>

  <div class="small" style="margin-top:8px;">
    Si vence la reserva, tus números se liberarán automáticamente.<br>
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;">{{ $verifyUrl }}</span>
  </div>
</div>
@endsection
