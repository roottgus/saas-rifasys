<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Servicio Rifasys – {{ $contract->contract_number }}</title>
    <style>
        /* ======== CONFIGURACIÓN DE PÁGINA PROFESIONAL ======== */
        @page { 
            margin: 120px 50px 100px 50px;
            size: letter;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            line-height: 1.6;
            background: white;
        }

        /* ======== HEADER PROFESIONAL FIJO ======== */
        .header {
            position: fixed;
            top: -100px;
            left: -50px;
            right: -50px;
            height: 100px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 3px solid #0ea5a3;
            padding: 0 50px;
        }

        .header-content {
            width: 100%;
            height: 100%;
        }

        .header-logo {
            height: 45px;
            width: auto;
            display: block;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f3a71;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
            letter-spacing: 0.3px;
        }

        .contract-number {
            background: #0ea5a3;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
            margin-top: 5px;
        }

        /* ======== FOOTER PROFESIONAL FIJO ======== */
        .footer {
            position: fixed;
            bottom: -80px;
            left: -50px;
            right: -50px;
            height: 70px;
            background: #f8f9fa;
            border-top: 2px solid #e2e8f0;
            padding: 15px 50px;
        }

        .footer-content {
            width: 100%;
        }

        .footer-left {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
        }

        .footer-company {
            font-weight: bold;
            color: #475569;
            font-size: 10px;
        }

        .footer-right {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }

        .page-number {
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        /* ======== CONTENIDO PRINCIPAL ======== */
        main {
            margin-top: 10px;
        }

        /* ======== CAJA DE VERIFICACIÓN PROFESIONAL ======== */
        .verification-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5a3;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            page-break-inside: avoid;
        }

        .verification-header {
            font-size: 12px;
            font-weight: bold;
            color: #0f3a71;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #bae6fd;
        }

        .verification-content {
            width: 100%;
        }

        .verification-item {
            font-size: 11px;
            color: #334155;
            margin-bottom: 6px;
        }

        .verification-label {
            font-weight: bold;
            color: #0f3a71;
            display: inline-block;
            min-width: 100px;
        }

        .verification-value {
            color: #1e293b;
        }

        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 3px 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #f59e0b;
        }

        .qr-container {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }

        .qr-image {
            width: 80px;
            height: 80px;
        }

        .qr-text {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }

        /* ======== TABLA DE DATOS PROFESIONAL ======== */
        .client-data-section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-header {
            background: linear-gradient(135deg, #0f3a71 0%, #1e5a8e 100%);
            color: white;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .data-table tr:last-child {
            border-bottom: none;
        }

        .data-table th {
            background: #f8f9fa;
            color: #0f3a71;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            padding: 12px 20px;
            width: 30%;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .data-table td {
            padding: 12px 20px;
            font-size: 11px;
            color: #1e293b;
        }

        /* ======== CLÁUSULAS PROFESIONALES ======== */
        .clauses-section {
            margin-top: 30px;
        }

        .clause-container {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .clause-title {
            background: linear-gradient(90deg, #0f3a71 0%, transparent 100%);
            color: white;
            padding: 8px 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-radius: 5px 0 0 5px;
        }

        .clause-content {
            padding: 0 15px;
            text-align: justify;
            font-size: 11px;
            line-height: 1.7;
            color: #374151;
        }

        .clause-content b {
            color: #0f3a71;
            font-weight: bold;
        }

        .important-text {
            background: #fef3c7;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }

        /* ======== SECCIÓN DE FIRMAS PROFESIONAL ======== */
        .signatures-section {
            margin-top: 40px;
            page-break-inside: avoid;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
        }

        .signatures-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #0f3a71;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #cbd5e1;
        }

        .signatures-table {
            width: 100%;
            table-layout: fixed;
        }

        .signature-cell {
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-box {
            background: white;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            min-height: 120px;
            margin-bottom: 15px;
            position: relative;
        }

        .signature-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .signature-image {
            max-height: 70px;
            max-width: 90%;
            margin: 10px auto;
        }

        .signature-line {
            border-bottom: 2px solid #cbd5e1;
            width: 80%;
            margin: 50px auto 10px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f3a71;
            margin-top: 10px;
        }

        .signature-id {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }

        .signature-date {
            font-size: 10px;
            color: #475569;
            margin-top: 5px;
            background: #e2e8f0;
            padding: 3px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        /* ======== UTILIDADES ======== */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; }
        .w-full { width: 100%; }
        .avoid-break { page-break-inside: avoid; }
        
        /* ======== WATERMARK (OPCIONAL) ======== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(14, 165, 163, 0.05);
            font-weight: bold;
            text-transform: uppercase;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- MARCA DE AGUA OPCIONAL -->
    <div class="watermark">Publienred</div>

    <!-- HEADER PROFESIONAL -->
    <div class="header">
        <table class="header-content">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    <img src="{{ public_path('img/logo-publienred.png') }}" alt="Publienred" class="header-logo">
                </td>
                <td style="width: 50%; text-align: center; vertical-align: middle;">
                    <div class="header-title">Contrato de Servicio</div>
                    <div class="header-subtitle">Software as a Service (SaaS) - Plataforma Rifasys</div>
                    <span class="contract-number">CONTRATO N° {{ $contract->contract_number }}</span>
                </td>
                <td style="width: 25%; text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('img/logo-rifasys.png') }}" alt="Rifasys" class="header-logo">
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER PROFESIONAL -->
    <div class="footer">
        <table class="footer-content">
            <tr>
                <td style="width: 70%; vertical-align: middle;">
                    <div class="footer-company">Publicidad en Red C.A.</div>
                    <div class="footer-left">
                        RIF: J-29761832-5 · Merida, Venezuela<br>
                        Documento generado electrónicamente · Soporte: admin@publienred.com
                    </div>
                </td>
                <td style="width: 30%; vertical-align: middle;" class="footer-right">
                    <span class="page-number">Página {PAGE_NUM} de {PAGE_COUNT}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <main>
        <!-- CAJA DE VERIFICACIÓN -->
        <div class="verification-box">
            <div class="verification-header">⬢ Información de Verificación Digital</div>
            <table class="verification-content">
                <tr>
                    <td style="width: 70%;">
                        <div class="verification-item">
                            <span class="verification-label">UUID / Folio:</span>
                            <span class="verification-value">{{ $contract->uuid }}</span>
                        </div>
                        <div class="verification-item">
                            <span class="verification-label">Fecha Emisión:</span>
                            <span class="verification-value">{{ $contract->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="verification-item">
                            <span class="verification-label">Estado:</span>
                            @if ($contract->status === 'signed')
                                <span class="status-badge">✓ FIRMADO DIGITALMENTE</span>
                            @else
                                <span class="status-badge status-pending">⏳ PENDIENTE DE FIRMA</span>
                            @endif
                        </div>
                        <div class="verification-item">
                            <span class="verification-label">Hash SHA-256:</span>
                            <span class="verification-value" style="font-size: 9px;">{{ substr(hash('sha256', $contract->uuid), 0, 32) }}...</span>
                        </div>
                    </td>
                    <td style="width: 30%; text-align: right;">
                        <div class="qr-container">
                            @php
                                $qrPath = null; // public_path('storage/qrcodes/'.$contract->uuid.'.png');
                            @endphp
                            @if (!empty($qrPath) && file_exists($qrPath))
                                <img src="{{ $qrPath }}" alt="QR" class="qr-image">
                                <div class="qr-text">Verificar documento</div>
                            @else
                                <div style="width: 80px; height: 80px; background: #f3f4f6; border: 2px dashed #cbd5e1; border-radius: 8px; margin: 0 auto; display: table;">
                                    <div style="display: table-cell; vertical-align: middle; text-align: center;">
                                        <div style="font-size: 24px; color: #cbd5e1;">QR</div>
                                    </div>
                                </div>
                                <div class="qr-text">Código QR</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- DATOS DEL CLIENTE -->
        <div class="client-data-section">
            <div class="section-header">⬢ Información del Cliente y Servicio</div>
            <table class="data-table">
                <tr>
                    <th>Razón Social / Nombre</th>
                    <td><strong>{{ $contract->client_name }}</strong></td>
                </tr>
                <tr>
                    <th>Documento de Identidad</th>
                    <td>C.I. / RIF: {{ $contract->client_id_number }}</td>
                </tr>
                <tr>
                    <th>Correo Electrónico</th>
                    <td>{{ $contract->client_email }}</td>
                </tr>
                <tr>
                    <th>Teléfono de Contacto</th>
                    <td>{{ $contract->client_phone }}</td>
                </tr>
                <tr>
                    <th>Dirección Fiscal</th>
                    <td>{{ $contract->client_address }}</td>
                </tr>
                <tr>
                    <th>Rifa / Sorteo</th>
                    <td><strong>{{ $contract->raffle_name }}</strong></td>
                </tr>
                <tr>
                    <th>Fecha de Contratación</th>
                    <td>{{ $contract->created_at->format('d \\d\\e F \\d\\e\\l Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- CLÁUSULAS DEL CONTRATO -->
        <div class="clauses-section">
            <div class="clause-container">
                <div class="clause-title">Cláusula Primera: Objeto del Contrato</div>
                <div class="clause-content">
                    Publicidad en Red C.A. (en adelante "LA EMPRESA") otorga al CLIENTE una licencia de uso del software 
                    Rifasys bajo modalidad SaaS (Software as a Service) para la gestión, administración y operación de la 
                    rifa especificada en este contrato. El servicio incluye hosting, mantenimiento, soporte técnico y 
                    actualizaciones durante la vigencia del contrato.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Segunda: Precio y Condiciones de Pago</div>
                <div class="clause-content">
                    El valor total del servicio es de <b>USD 150,00 (CIENTO CINCUENTA DÓLARES ESTADOUNIDENSES)</b> por cada 
                    rifa creada en la plataforma. Este monto incluye: configuración inicial del sistema, personalización con 
                    dominio exclusivo, soporte técnico durante la vigencia de la rifa, almacenamiento seguro de datos y 
                    certificación digital de resultados. El pago debe realizarse en su totalidad previo a la activación del servicio.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Tercera: Responsabilidades Legales y Regulatorias</div>
                <div class="clause-content">
                    El CLIENTE declara conocer y acepta que es el <span class="important-text">único responsable</span> de obtener, 
                    mantener vigente y cumplir con todos los permisos, licencias y autorizaciones requeridas por la 
                    <b>Comisión Nacional de Lotería (CONALOT)</b> y cualquier otro organismo regulador competente. LA EMPRESA 
                    actúa exclusivamente como proveedor de tecnología y <b>no asume ninguna responsabilidad legal, administrativa, 
                    civil o penal</b> derivada del uso del sistema sin los permisos correspondientes o del incumplimiento de 
                    normativas aplicables.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Cuarta: Transparencia y Determinación de Ganadores</div>
                <div class="clause-content">
                    Rifasys opera con total transparencia. El sistema <b>no determina, manipula ni influye</b> en la selección 
                    de ganadores. Los resultados se basan exclusivamente en los sorteos oficiales de las loterías nacionales 
                    o internacionales seleccionadas por el CLIENTE. LA EMPRESA garantiza la integridad del proceso mediante 
                    registros auditables y trazabilidad completa de todas las operaciones.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Quinta: Documentación y Cumplimiento</div>
                <div class="clause-content">
                    El CLIENTE debe proporcionar: (i) Copia legible de su documento de identidad, (ii) Permiso CONALOT vigente 
                    (cuando aplique), (iii) Documentación fiscal requerida. La ausencia del permiso CONALOT no impide la 
                    prestación del servicio, pero el CLIENTE asume toda responsabilidad legal y exonera completamente a 
                    LA EMPRESA de cualquier consecuencia derivada de operar sin dicha autorización.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Sexta: Vigencia y Terminación</div>
                <div class="clause-content">
                    Este contrato entra en vigor desde su firma digital o aceptación electrónica y permanece activo hasta la 
                    conclusión de la rifa especificada. Una vez finalizada, el sistema bloqueará automáticamente la venta de 
                    boletos. Para realizar nuevas rifas, se requiere la suscripción de un nuevo contrato. LA EMPRESA se 
                    reserva el derecho de suspender o terminar el servicio ante incumplimientos contractuales o uso indebido.
                </div>
            </div>

            <div class="clause-container">
                <div class="clause-title">Cláusula Séptima: Protección de Datos</div>
                <div class="clause-content">
                    LA EMPRESA garantiza el tratamiento confidencial de todos los datos del CLIENTE y sus usuarios finales, 
                    cumpliendo con las normativas vigentes de protección de datos. La información será utilizada exclusivamente 
                    para la prestación del servicio contratado y no será compartida con terceros sin autorización expresa.
                </div>
            </div>
        </div>

        <!-- SECCIÓN DE FIRMAS -->
        <div class="signatures-section">
            <div class="signatures-title">Firmas y Aceptación del Contrato</div>
            
            <table class="signatures-table">
                <tr>
                    <td class="signature-cell">
                        <div class="signature-label">Por la Empresa Proveedora</div>
                        <div class="signature-box">
                            @php 
                                $selloPath = public_path('img/sello-empresa.png'); 
                            @endphp
                            @if(file_exists($selloPath))
                                <img src="{{ $selloPath }}" class="signature-image" alt="Sello">
                            @else
                                <div class="signature-line"></div>
                            @endif
                        </div>
                        <div class="signature-name">Publicidad en Red C.A.</div>
                        <div class="signature-id">RIF: J-XXXXXXXXX-X</div>
                        <div class="signature-date">
                            Fecha: {{ $contract->signed_at ? $contract->signed_at->format('d/m/Y') : now()->format('d/m/Y') }}
                        </div>
                    </td>
                    
                    <td class="signature-cell">
                        <div class="signature-label">Por el Cliente Contratante</div>
                        <div class="signature-box">
                            @php
                                $firmaPath = $contract->signature_image_path 
                                    ? public_path('storage/'.$contract->signature_image_path) 
                                    : null;
                            @endphp
                            @if($firmaPath && file_exists($firmaPath))
                                <img src="{{ $firmaPath }}" class="signature-image" alt="Firma">
                            @else
                                <div class="signature-line"></div>
                            @endif
                        </div>
                        <div class="signature-name">{{ $contract->client_name }}</div>
                        <div class="signature-id">C.I.: {{ $contract->client_id_number }}</div>
                        <div class="signature-date">
                            Fecha: {{ $contract->signed_at ? $contract->signed_at->format('d/m/Y') : '___/___/_____' }}
                        </div>
                    </td>
                </tr>
            </table>

            @if($contract->signed_at)
            <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #64748b;">
                <div style="background: #e0f2fe; padding: 10px; border-radius: 8px; display: inline-block;">
                    <strong>Firmado digitalmente el {{ $contract->signed_at->format('d/m/Y') }} a las {{ $contract->signed_at->format('H:i:s') }}</strong><br>
                    IP: {{ $contract->signature_ip ?? 'No registrada' }} · 
                    Navegador: {{ $contract->signature_user_agent ?? 'No registrado' }}
                </div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>