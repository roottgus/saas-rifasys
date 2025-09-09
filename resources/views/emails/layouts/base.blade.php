@php
  // Usar color fijo para evitar HtmlString
  $brand = '#2563eb'; // Color fijo por ahora

  // Resto de variables como están...
  $logoUrl = $logoUrl
    ?? (
      isset($tenant) && method_exists($tenant, 'brandSettings') && $tenant->brandSettings()->first()?->logo_path
        ? \Illuminate\Support\Facades\Storage::url($tenant->brandSettings()->first()->logo_path)
        : null
    );

  $title      = $title ?? '';
  $subtitle   = $subtitle ?? '';
  $badge      = $badge ?? null;
  $footerGifs = $footerGifs ?? [];
  $footerText = $footerText ?? 'Powered by Rifasys • Sistema 100% seguro';
  $preheader  = $preheader ?? '';
@endphp
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $title ?: 'Notificación' }}</title>
  <meta name="x-apple-disable-message-reformatting">
  <style>
    body{margin:0;padding:0;background:#f5f7fb;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Helvetica,Arial}
    .wrap{max-width:640px;margin:24px auto}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.04)}
    .bar{height:4px;background:{{ $brand }}}
    .box{padding:22px 26px}
    .head{padding:18px 26px 8px; text-align:center}
    .logo{height:42px;max-width:140px;}
    .h1{font-size:20px;font-weight:800;color:#0f172a;margin:10px 0 0;text-align:center}
    .p{font-size:14px;color:#334155;margin:0 0 10px;line-height:1.55}
    .muted{color:#64748b}
    .btn{display:inline-block;background:{{ $brand }};color:#fff;text-decoration:none;font-weight:800;padding:11px 18px;border-radius:10px}
    .pill{display:inline-block;padding:6px 12px;border-radius:999px;font-weight:700;font-size:13px;line-height:1;}
    .table{width:100%;border-collapse:separate;border-spacing:0}
    .cell{border:1px solid #e5e7eb;border-radius:12px;background:#fafafa}
    .small{font-size:12px;color:#64748b}
    .foot{padding:16px 0;color:#94a3b8;text-align:center;font-size:12px}
    .footer-gif{padding:14px 10px;}
  </style>
</head>
<body>
  <!-- preheader (oculto) -->
  <div style="display:none;max-height:0;overflow:hidden;opacity:0">{{ $preheader }}</div>

  <div class="wrap">
    <div class="card">
      <div class="bar"></div>
      <div class="head">

        {{-- Logo --}}
        @if($logoUrl)
          <div style="margin-bottom:16px;">
            <img src="{{ $logoUrl }}" alt="Logo" class="logo">
          </div>
        @endif

        {{-- Badge de estado --}}
        @if($badge && !empty($badge['text']))
          <div style="margin-bottom:8px;">
            <span class="pill"
                  style="background:{{ $badge['bg'] ?? '#f1f5f9' }};color:{{ $badge['color'] ?? '#222' }};">
              {{ $badge['text'] }}
            </span>
          </div>
        @endif

        {{-- Título/subtítulo --}}
        @if($title)
          <h1 class="h1" style="margin-top:10px;">{{ $title }}</h1>
        @endif
        @if($subtitle)
          <div class="p muted" style="margin-top:5px">{{ $subtitle }}</div>
        @endif
      </div>

      <div class="box">
        @yield('content')
      </div>

      {{-- FOOTER GIFS (Decorativos estilo BDV/Banesco) --}}
      @if($footerGifs && count($footerGifs))
        <table width="100%" align="center" style="background:#0f172a;border-radius:12px;margin:0 0 10px 0;">
          <tr>
            @foreach($footerGifs as $gifUrl)
              <td align="center" class="footer-gif">
                <img src="{{ $gifUrl }}" style="height:42px;max-width:64px;border-radius:8px;" alt="GIF">
              </td>
            @endforeach
          </tr>
        </table>
      @endif

    </div>
    <div class="foot">
      {{ is_string($footerText) ? $footerText : (is_a($footerText, 'Illuminate\Support\HtmlString') ? $footerText->toHtml() : $footerText) }}
    </div>

  </div>
</body>
</html>
