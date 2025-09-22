@extends('emails.layouts.base')

@section('content')
@php
  $primary = '#2563eb';
  $tenant = $tenant ?? $order->tenant;
  $rifa = $order->rifa;
  $tickets = optional($order->items)->pluck('numero')->filter()->sort();
  $monto = number_format((float)($order->total_amount ?? 0), 2, '.', ',');
  $verifyUrl = $verifyUrl ?? url('/t/'.($tenant->slug ?? $tenant->id).'/verify?code='.$order->code);

  // Saludo (primer nombre)
  $firstName = $order->customer_name
    ? \Illuminate\Support\Str::of($order->customer_name)->trim()->explode(' ')->first()
    : null;
  $saludo = $firstName ? "Hola {$firstName}," : "¡Hola!";

  // Método de pago + banco (si aplica)
  $metodo = $order->paymentAccount->etiqueta ?? 'Transferencia';
  if (!empty(optional($order->paymentAccount)->banco)) {
    $metodo .= ' – '.optional($order->paymentAccount)->banco;
  }

  // Referencia de pago
  $referencia = $order->referencia ?: '–';

  // Fecha del sorteo: draw_at (si existe)
  $tz = $tenant->timezone ?? 'America/Caracas';
  $sorteoFmt = null;
  if (!empty($rifa?->draw_at)) {
    try {
      $dt = $rifa->draw_at instanceof \Carbon\Carbon
        ? $rifa->draw_at->copy()
        : \Carbon\Carbon::parse($rifa->draw_at);
      $sorteoFmt = $dt->timezone($tz)->translatedFormat("l d \\de F Y \\a \\las h:i A");
    } catch (\Throwable $e) {
      $sorteoFmt = null;
    }
  }
@endphp

{{-- Saludo personalizado --}}
<p class="p" style="margin:0 0 16px;">
  <strong>{{ $saludo }}</strong> Gracias por tu compra en {{ $tenant->name ?? config('app.name') }}.
</p>

{{-- Tabla de detalles usando el estilo existente --}}
<table class="table" style="margin:16px 0;">
  <tr>
    <td class="cell">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="padding:6px 0;width:38%;color:#64748b;font-size:13px;font-weight:600;">
            Código de orden:
          </td>
          <td style="padding:6px 0;font-weight:700;color:#0f172a;">
            {{ $order->code }}
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Rifa:
          </td>
          <td style="padding:6px 0;font-weight:700;color:#0f172a;">
            {{ $rifa->nombre ?? $rifa->titulo ?? '–' }}
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;vertical-align:top;color:#64748b;font-size:13px;font-weight:600;">
            Boletos:
          </td>
          <td style="padding:6px 0;">
            @if($tickets && $tickets->count())
              <div style="line-height:1.6;">
                @foreach($tickets as $num)
                  <span class="pill" style="border:2px solid {{ $primary }};background:#fff;color:{{ $primary }};margin:2px 4px 2px 0;padding:2px 8px;border-radius:12px;display:inline-block;font-weight:700;font-size:12px;">
                    #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
                  </span>
                @endforeach
              </div>
            @else
              <span style="color:#64748b;">–</span>
            @endif
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Total pagado:
          </td>
          <td style="padding:6px 0;font-weight:700;color:#059669;font-size:16px;">
            ${{ $monto }}
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Método de pago:
          </td>
          <td style="padding:6px 0;color:#0f172a;">
            {{ $metodo }}
          </td>
        </tr>
        @if($referencia !== '–')
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Referencia:
          </td>
          <td style="padding:6px 0;color:#0f172a;font-family:monospace;font-size:13px;">
            {{ $referencia }}
          </td>
        </tr>
        @endif
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Fecha del sorteo:
          </td>
          <td style="padding:6px 0;">
            @if($sorteoFmt)
              <strong style="color:#0f172a;">{{ $sorteoFmt }}</strong>
              <br><span style="color:#6b7280;font-size:11px;">({{ $tz }})</span>
            @else
              <span style="color:#64748b;">Se anunciará próximamente</span>
            @endif
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#64748b;font-size:13px;font-weight:600;">
            Estado:
          </td>
          <td style="padding:6px 0;">
            <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:12px;display:inline-block;font-weight:600;font-size:12px;">
              ✅ Pagado y confirmado
            </span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{{-- Botón principal --}}
<div style="text-align:center;margin:32px 0 20px;">
  <a href="{{ $verifyUrl }}" style="background:{{ $primary }};color:#ffffff;padding:12px 28px;border-radius:8px;text-decoration:none;display:inline-block;font-weight:600;font-size:14px;">
    🎫 Ver mis boletos
  </a>
</div>

{{-- URL de respaldo --}}
<div style="text-align:center;">
  <p style="margin:0;line-height:1.5;color:#6b7280;font-size:12px;">
    Si el botón no funciona, copia y pega este enlace:<br>
    <span style="word-break:break-all;color:#2563eb;font-family:monospace;font-size:11px;">{{ $verifyUrl }}</span>
  </p>
</div>

{{-- Mensaje de buena suerte --}}
<div style="background:#eff6ff;border:1px solid #dbeafe;border-radius:8px;padding:16px;margin-top:24px;text-align:center;">
  <p style="margin:0;color:#1e40af;font-size:14px;">
    🍀 <strong>¡Buena suerte!</strong> Te notificaremos cuando se realice el sorteo.
  </p>
</div>

{{-- Footer simple --}}
@if(!empty($footerText))
<div style="text-align:center;margin-top:32px;padding-top:20px;border-top:1px solid #e5e7eb;">
  <p style="margin:0;color:#9ca3af;font-size:11px;">
    {{ $footerText }}
  </p>
</div>
@endif

@endsection