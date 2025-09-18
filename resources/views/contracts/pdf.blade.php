<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Servicio Rifasys — {{ $contract->contract_number }}</title>
    <style>
        /* ======== REGLAS 100% COMPATIBLES DOMPDF ======== */
        @page { margin: 110px 36px 80px 36px; } /* top right bottom left */

        body {
            font-family: DejaVu Sans, sans-serif; /* DejaVu trae latín completo */
            font-size: 12px;
            color: #1f2937; /* slate-800 */
            line-height: 1.45;
        }

        /* Header / Footer usando posiciones fijas (Dompdf sí soporta) */
        .header {
            position: fixed;
            top: -90px;
            left: 0; right: 0;
            height: 90px;
            border-bottom: 2px solid #0ea5a3; /* teal */
        }
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0; right: 0;
            height: 60px;
            border-top: 1px solid #cbd5e1;
            color: #475569;
            font-size: 11px;
        }

        /* Tablas generales */
        table { border-collapse: collapse; }
        .w-full { width: 100%; }
        .align-middle td, .align-middle th { vertical-align: middle; }

        .title { font-size: 16px; font-weight: bold; color: #0f3a71; }
        .subtitle { font-size: 12px; color: #475569; }
        .badge {
            display: inline-block;
            background: #0ea5a3; color: #fff;
            padding: 2px 7px; border-radius: 10px; font-weight: bold; font-size: 11px;
        }
        .section-title { font-size: 13px; font-weight: bold; color: #0f3a71; margin: 14px 0 6px; }
        .clause { text-align: justify; margin: 4px 0; }

        /* Tabla de datos del cliente */
        .data { width: 100%; margin: 8px 0 12px 0; }
        .data th {
            width: 26%; text-align: left; font-size: 12px; color: #0f3a71;
            background: #f1f5f9; border-bottom: 1px solid #e2e8f0; padding: 6px 10px;
        }
        .data td {
            font-size: 12px; border-bottom: 1px solid #eef2f7; padding: 6px 10px;
        }

        /* Bloque de verificación (sin flex, solo tabla) */
        .verify {
            width: 100%; border: 1px solid #bae6fd; background: #f0f9ff; border-radius: 8px;
            -webkit-border-radius: 8px; /* Dompdf */
            margin: 10px 0 14px 0;
        }
        .verify td { padding: 8px 10px; }
        .verify .kv { font-size: 12px; color: #334155; }
        .verify .kv b { color: #0f3a71; }
        .qr-box { text-align: right; }
        .qr-border { border: 1px solid #e2e8f0; padding: 3px; display: inline-block; }

        /* Firmas (tabla con 2 columnas) */
        .sign-table { width: 100%; margin-top: 16px; table-layout: fixed; }
        .sign-cell { width: 50%; text-align: center; }
        .sign-box {
            height: 90px; border-bottom: 1px solid #cbd5e1; margin: 0 auto 6px auto;
            width: 85%; /* asegura ancho consistente */
        }
        .sign-img { max-height: 90px; max-width: 100%; }
        .sign-label { font-size: 11px; color: #64748b; }
        .sign-name { font-size: 12px; font-weight: bold; margin-top: 4px; }
        .sign-date { font-size: 11px; color: #6b7280; margin-top: 2px; }

        .muted { color: #6b7280; }
        .small { font-size: 11px; }
        .xs { font-size: 10px; }

        /* Evitar cortes bruscos en bloques críticos */
        .avoid-break { page-break-inside: avoid; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <table class="w-full align-middle" style="margin-top:10px;">
            <tr>
                <td style="width: 33%; text-align:left;">
                    <img src="{{ public_path('img/logo-publienred.png') }}" alt="Publienred" style="height:56px;">
                </td>
                <td style="width: 34%; text-align:center;">
                    <div class="title">CONTRATO DE SERVICIO (SaaS)</div>
                    <div class="subtitle">Plataforma Rifasys · Contrato N° <b>{{ $contract->contract_number }}</b></div>
                </td>
                <td style="width: 33%; text-align:right;">
                    <img src="{{ public_path('img/logo-rifasys.png') }}" alt="Rifasys" style="height:48px;">
                </td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <table class="w-full" style="margin-top:8px;">
            <tr>
                <td style="text-align:left;">
                    <div class="small">Publicidad en Red C.A. &middot; Rifasys &copy; {{ now()->year }}</div>
                    <div class="xs muted">Documento generado automáticamente. Soporte: admin@publienred.com</div>
                </td>
                <td style="text-align:right;" class="small">
                    Página <span class="pagenum">{PAGE_NUM} / {PAGE_COUNT}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- CONTENIDO --}}
    <main>

        {{-- VERIFICACIÓN / FOLIO --}}
        <table class="verify avoid-break">
            <tr>
                <td>
                    <div class="kv"><b>Folio / UUID:</b> {{ $contract->uuid }}</div>
                    <div class="kv"><b>Emitido:</b> {{ $contract->created_at->format('d/m/Y H:i') }}</div>
                    <div class="kv">
                        <b>Estado:</b>
                        @if ($contract->status === 'signed')
                            <span class="badge">FIRMADO</span>
                        @else
                            <span class="muted">Pendiente de firma</span>
                        @endif
                    </div>
                </td>
                <td class="qr-box" style="width:120px;">
                    @php
                        // $qrPath = public_path('storage/qrcodes/'.$contract->uuid.'.png');
                    @endphp
                    @if (!empty($qrPath) && file_exists($qrPath))
                        <span class="qr-border">
                            <img src="{{ $qrPath }}" alt="QR" style="height:95px;width:95px;">
                        </span>
                        <div class="xs muted" style="margin-top:4px;">Verifique escaneando</div>
                    @else
                        <div class="xs muted">(QR opcional)</div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- DATOS DEL CLIENTE --}}
        <table class="data avoid-break">
            <tr><th>Cliente</th><td>{{ $contract->client_name }} (C.I.: {{ $contract->client_id_number }})</td></tr>
            <tr><th>Rifa</th><td>{{ $contract->raffle_name }}</td></tr>
            <tr><th>Email</th><td>{{ $contract->client_email }}</td></tr>
            <tr><th>Teléfono</th><td>{{ $contract->client_phone }}</td></tr>
            <tr><th>Dirección</th><td>{{ $contract->client_address }}</td></tr>
            <tr><th>Fecha de emisión</th><td>{{ $contract->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>

        {{-- CLÁUSULAS --}}
        <div class="section-title">1. Precio, alcance y vigencia</div>
        <div class="clause">
            El precio del servicio es de <b>USD 150 (ciento cincuenta dólares estadounidenses)</b> por cada rifa creada en la
            plataforma Rifasys. Incluye configuración técnica, soporte y la gestión de un dominio exclusivo para la rifa
            especificada en este contrato.
        </div>
        <div class="clause">
            Finalizada la rifa, el sistema bloqueará la venta de boletos. Para ejecutar nuevas rifas se requiere la
            suscripción de un nuevo contrato y su correspondiente firma/aceptación.
        </div>

        <div class="section-title">2. Responsabilidad legal (CONALOT)</div>
        <div class="clause">
            El cliente es el único responsable de la obtención y vigencia de permisos/licencias ante la
            <b>Comisión Nacional de Lotería (CONALOT)</b> y cualquier ente regulador aplicable. Publienred C.A. / Rifasys
            provee exclusivamente el software y <b>no asume responsabilidad</b> por el uso del sistema sin dichos permisos.
        </div>

        <div class="section-title">3. Determinación del ganador</div>
        <div class="clause">
            Rifasys no determina ni manipula ganadores; estos se eligen exclusivamente según los resultados oficiales de la
            lotería seleccionada por el cliente.
        </div>

        <div class="section-title">4. Documentación obligatoria</div>
        <div class="clause">
            El cliente declara haber entregado copia de su cédula y, de disponer, el permiso CONALOT. Si no anexa dicho
            permiso, asume total responsabilidad legal por la realización de rifas y exonera a Publienred C.A. de cualquier
            reclamo o consecuencia derivada.
        </div>

        <div class="section-title">5. Firma y aceptación</div>
        <div class="clause">
            El presente contrato es válido una vez firmado digitalmente por el cliente o aceptado a través de la plataforma,
            dejando registro de IP, fecha y consentimiento informado.
        </div>

        {{-- FIRMAS --}}
        <div class="section-title">Firmas</div>
        <table class="sign-table avoid-break">
            <tr>
                <td class="sign-cell">
                    <div class="sign-label">Firma y sello de la empresa</div>
                    @php $selloPath = public_path('img/sello-empresa.png'); @endphp
                    <div class="sign-box">
                        @if(file_exists($selloPath))
                            <img src="{{ $selloPath }}" class="sign-img" alt="Sello empresa">
                        @endif
                    </div>
                    <div class="sign-name">Publicidad en Red C.A.</div>
                    <div class="sign-date">Fecha: {{ $contract->signed_at ? $contract->signed_at->format('d/m/Y') : '____/____/______' }}</div>
                </td>

                <td class="sign-cell">
                    <div class="sign-label">Firma electrónica del cliente</div>
                    @php
                        $firmaClientePath = $contract->signature_image_path ? public_path('storage/'.$contract->signature_image_path) : null;
                    @endphp
                    <div class="sign-box">
                        @if($firmaClientePath && file_exists($firmaClientePath))
                            <img src="{{ $firmaClientePath }}" class="sign-img" alt="Firma cliente">
                        @endif
                    </div>
                    <div class="sign-name">{{ $contract->client_name }}</div>
                    <div class="sign-date">Fecha: {{ $contract->signed_at ? $contract->signed_at->format('d/m/Y') : '____/____/______' }}</div>
                </td>
            </tr>
        </table>

    </main>
</body>
</html>
