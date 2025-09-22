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
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?: 'Notificación' }}</title>
  <meta name="x-apple-disable-message-reformatting">
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
  <style>
    /* Reset para clientes de email */
    * { box-sizing: border-box; }
    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    table, td { 
      border-collapse: collapse; 
      mso-table-lspace: 0pt; 
      mso-table-rspace: 0pt; 
    }
    img { 
      -ms-interpolation-mode: bicubic; 
      max-width: 100%; 
      height: auto; 
      border: 0; 
      outline: none; 
      text-decoration: none; 
    }

    /* Estilos principales */
    body{
      margin:0;
      padding:0;
      background:#f5f7fb;
      font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
    }
    .wrap{
      max-width:640px;
      margin:24px auto;
      padding:0 20px;
    }
    .card{
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:16px;
      overflow:hidden; 
      box-shadow:0 4px 6px rgba(0,0,0,.05);
    }
    .bar{
      height:4px;
      background:{{ $brand }};
    }
    .box{
      padding:22px 26px;
    }
    .head{
      padding:18px 26px 8px; 
      text-align:center;
    }
    .logo{
      height:42px;
      max-width:140px;
    }
    .h1{
      font-size:20px;
      font-weight:800;
      color:#0f172a;
      margin:10px 0 0;
      text-align:center;
      line-height:1.3;
    }
    .p{
      font-size:14px;
      color:#334155;
      margin:0 0 10px;
      line-height:1.55;
    }
    .muted{
      color:#64748b;
    }
    .btn{
      display:inline-block;
      background:{{ $brand }};
      color:#fff !important;
      text-decoration:none;
      font-weight:700;
      padding:12px 20px;
      border-radius:10px;
      font-size:14px;
    }
    .pill{
      display:inline-block;
      padding:6px 12px;
      border-radius:999px;
      font-weight:700;
      font-size:13px;
      line-height:1;
    }
    .table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
    }
    .cell{
      border:1px solid #e5e7eb;
      border-radius:12px;
      background:#fafafa;
      padding:20px;
    }
    .small{
      font-size:12px;
      color:#64748b;
      line-height:1.4;
    }
    .foot{
      padding:16px 0;
      color:#94a3b8;
      text-align:center;
      font-size:12px;
      line-height:1.4;
    }
    .footer-gif{
      padding:14px 10px;
    }

    /* Responsive */
    @media only screen and (max-width: 600px) {
      .wrap { 
        padding: 0 15px !important; 
        margin: 15px auto !important;
      }
      .box { 
        padding: 18px 20px !important; 
      }
      .head { 
        padding: 15px 20px 8px !important; 
      }
      .h1 { 
        font-size: 18px !important; 
      }
      .btn { 
        padding: 14px 24px !important; 
        font-size: 16px !important; 
      }
    }
  </style>
</head>
<body>
  <!-- preheader (oculto) -->
  @if($preheader)
  <div style="display:none;mso-hide:all;font-size:1px;color:#f5f7fb;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    {{ $preheader }}
  </div>
  @endif

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
        <div style="background:#0f172a;border-radius:0 0 12px 12px;margin:0;">
          <table width="100%" align="center" style="margin:0;">
            <tr>
              @foreach($footerGifs as $gifUrl)
                <td align="center" class="footer-gif">
                  <img src="{{ $gifUrl }}" style="height:42px;max-width:64px;border-radius:8px;" alt="🎉">
                </td>
              @endforeach
            </tr>
          </table>
        </div>
      @endif

    </div>
    <div class="foot">
      {!! $footerText !!}
    </div>

  </div>
</body>
</html>