@extends('emails.layouts.base')

@section('content')
@php
  $tickets = $order->items?->pluck('numero')->sort();
  $total   = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
  $primary = '#2563eb';
  $danger  = '#b91c1c';
@endphp

{{-- Badge de estado (rojo) --}}
<div style="text-align:center;margin-bottom:12px;">
  <span class="pill" style="background:#fee2e2;color:{{ $danger }};">Reserva cancelada</span>
</div>

{{-- Título y subtítulo --}}
<div style="text-align:center;margin-bottom:10px;">
  <h2 class="h1" style="margin:0 0 6px;font-size:22px;color:{{ $danger }};">
    Tu reserva fue cancelada
  </h2>
  <div class="p muted" style="font-size:14px;">
    Hola {{ $customerName ?? $order->customer_name ?? 'Cliente' }}, lamentamos informarte que tu reserva fue cancelada
    @if(isset($cancelReason))
      <br><span style="color:{{ $danger }};">Motivo: {{ $cancelReason }}</span>
    @endif
    <br>Los números reservados fueron liberados y están disponibles para otros participantes.
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
          <td style="padding:6px 0;font-weight:700;">{{ $rifa->titulo ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;">Números:</td>
          <td style="padding:6px 0;">
            @foreach($tickets as $num)
              <span class="pill" style="border:1.2px dashed {{ $danger }};background:#fff;color:{{ $danger }};">
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

{{-- Mensaje de ayuda --}}
<div class="small" style="margin:18px 0 0;text-align:center;color:{{ $danger }};">
  Si consideras que esto fue un error, contáctanos por WhatsApp o responde a este correo.<br>
  ¡Gracias por confiar en {{ $tenant->name ?? 'nosotros' }}!
</div>
@endsection
