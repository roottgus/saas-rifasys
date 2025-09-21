<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato Firmado - Rifasys</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    
    <!-- Wrapper -->
    <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                
                <!-- Main Container -->
                <table role="presentation" cellpadding="0" cellspacing="0" style="width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);">
                    
                    <!-- Header with Success Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0ea5a3 0%, #0f3a71 100%); padding: 50px 30px; text-align: center;">
                            
                            <!-- Success Icon -->
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto 20px;">
                                <tr>
                                    <td style="width: 90px; height: 90px; background-color: white; border-radius: 50%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                                        <span style="font-size: 48px; color: #10b981;">✓</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <h1 style="margin: 0; color: white; font-size: 32px; font-weight: bold; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                ¡Contrato Firmado Exitosamente!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">
                                Tu contrato ha sido procesado y validado digitalmente
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Success Badge -->
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin-bottom: 25px;">
                                <tr>
                                    <td style="background-color: #10b981; color: white; padding: 8px 20px; border-radius: 25px; font-size: 14px; font-weight: bold;">
                                        ✅ FIRMA DIGITAL COMPLETADA
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 16px; color: #374151; line-height: 1.6; margin: 0 0 25px 0;">
                                Estimado/a <strong>{{ $contract->client_name }}</strong>,
                            </p>
                            
                            <p style="font-size: 15px; color: #4b5563; line-height: 1.6; margin: 0 0 30px 0;">
                                Nos complace confirmar que tu contrato de servicio para la plataforma <strong>Rifasys</strong> ha sido 
                                <strong>firmado y validado exitosamente</strong>. Este documento tiene plena validez legal y ha quedado 
                                registrado en nuestros sistemas.
                            </p>
                            
                            <!-- Contract Details Box -->
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f8f9fa; border-left: 4px solid #0ea5a3; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 20px 0; color: #0f3a71; font-size: 18px;">
                                            📋 Detalles del Contrato
                                        </h3>
                                        
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #64748b; font-size: 14px; display: inline-block; min-width: 140px;">N° de Contrato:</span>
                                                    <strong style="color: #1e293b; font-size: 14px;">{{ $contract->contract_number }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #64748b; font-size: 14px; display: inline-block; min-width: 140px;">Rifa/Sorteo:</span>
                                                    <strong style="color: #1e293b; font-size: 14px;">{{ $contract->raffle_name }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #64748b; font-size: 14px; display: inline-block; min-width: 140px;">Fecha de Firma:</span>
                                                    <strong style="color: #1e293b; font-size: 14px;">{{ $contract->signed_at->format('d/m/Y') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #64748b; font-size: 14px; display: inline-block; min-width: 140px;">Hora de Firma:</span>
                                                    <strong style="color: #1e293b; font-size: 14px;">{{ $contract->signed_at->format('H:i:s') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #64748b; font-size: 14px; display: inline-block; min-width: 140px;">UUID/Folio:</span>
                                                    <strong style="color: #1e293b; font-size: 13px; font-family: monospace;">{{ $contract->uuid }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- PDF Attachment Notice -->
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: white; border: 2px dashed #cbd5e1; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px; text-align: center;">
                                        <div style="font-size: 36px; color: #0ea5a3; margin-bottom: 10px;">📄</div>
                                        <strong style="color: #0f3a71; font-size: 16px;">Documento PDF Adjunto</strong>
                                        <p style="color: #64748b; margin: 10px 0 0 0; font-size: 14px;">
                                            Tu contrato firmado se encuentra adjunto a este correo en formato PDF.<br>
                                            <strong>Guárdalo en un lugar seguro para futuras referencias.</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Next Steps -->
                            <h2 style="color: #0f3a71; font-size: 20px; margin: 0 0 20px 0;">
                                🚀 Próximos Pasos
                            </h2>
                            
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 0;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td style="padding: 8px 0; vertical-align: top;">
                                                    <span style="background: linear-gradient(135deg, #0ea5a3 0%, #0f3a71 100%); color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-weight: bold; margin-right: 12px;">1</span>
                                                    <span style="color: #374151; font-size: 14px;"><strong>Guarda este correo y el PDF adjunto</strong> en tus archivos importantes</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; vertical-align: top;">
                                                    <span style="background: linear-gradient(135deg, #0ea5a3 0%, #0f3a71 100%); color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-weight: bold; margin-right: 12px;">2</span>
                                                    <span style="color: #374151; font-size: 14px;"><strong>Accede a tu panel de control</strong> en Rifasys para comenzar a configurar tu rifa</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; vertical-align: top;">
                                                    <span style="background: linear-gradient(135deg, #0ea5a3 0%, #0f3a71 100%); color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-weight: bold; margin-right: 12px;">3</span>
                                                    <span style="color: #374151; font-size: 14px;"><strong>Revisa la documentación</strong> de ayuda para aprovechar todas las funcionalidades</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button -->
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin-bottom: 35px;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px;">
                                        <a href="{{ url('/dashboard') }}" style="display: inline-block; padding: 16px 32px; color: white; text-decoration: none; font-size: 16px; font-weight: bold;">
                                            Acceder a Mi Panel de Control
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Notice -->
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <strong style="color: #92400e; font-size: 16px;">🔒 Seguridad y Validación</strong>
                                        <p style="margin: 10px 0 0 0; color: #78350f; font-size: 14px; line-height: 1.6;">
                                            Este contrato ha sido firmado digitalmente y cuenta con:<br><br>
                                            • Registro de IP: <strong>{{ $contract->signature_ip ?? 'Registrada' }}</strong><br>
                                            • Hash de verificación SHA-256<br>
                                            • Timestamp certificado<br>
                                            • Respaldo en nuestros servidores seguros
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <h2 style="color: #0f3a71; font-size: 20px; margin: 0 0 20px 0;">
                                📞 ¿Necesitas Ayuda?
                            </h2>
                            
                            <p style="font-size: 14px; color: #4b5563; margin: 0 0 20px 0;">
                                Nuestro equipo de soporte está disponible para asistirte:
                            </p>
                            
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f1f5f9; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 15px 0; color: #0f3a71; font-size: 16px;">Canales de Soporte:</h4>
                                        
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="color: #64748b; font-size: 14px;">📧 Email:</span>
                                                    <a href="mailto:soporte@rifasys.com" style="color: #0ea5a3; text-decoration: none; font-size: 14px; font-weight: bold;">soporte@rifasys.com</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="color: #64748b; font-size: 14px;">📱 WhatsApp:</span>
                                                    <a href="https://wa.me/584120000000" style="color: #0ea5a3; text-decoration: none; font-size: 14px; font-weight: bold;">+58 412-000-0000</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="color: #64748b; font-size: 14px;">🌐 Centro de Ayuda:</span>
                                                    <a href="{{ url('/ayuda') }}" style="color: #0ea5a3; text-decoration: none; font-size: 14px; font-weight: bold;">rifasys.com/ayuda</a>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #cbd5e1;">
                                            <h4 style="margin: 0 0 10px 0; color: #0f3a71; font-size: 14px;">Horario de Atención:</h4>
                                            <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.6;">
                                                Lunes a Viernes: 8:00 AM - 6:00 PM<br>
                                                Sábados: 9:00 AM - 1:00 PM
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Divider -->
                            <div style="border-top: 2px solid #e5e7eb; margin: 35px 0;"></div>
                            
                            <!-- Closing Message -->
                            <p style="font-size: 15px; color: #374151; line-height: 1.6; text-align: center; margin: 0 0 20px 0;">
                                <strong>Gracias por confiar en Rifasys y Publicidad en Red C.A.</strong><br>
                                <span style="color: #6b7280;">Estamos comprometidos con brindarte la mejor plataforma tecnológica<br>
                                para la gestión de tus rifas y sorteos.</span>
                            </p>
                            
                            <p style="font-size: 14px; color: #64748b; text-align: center; margin: 0;">
                                Saludos cordiales,<br>
                                <strong style="color: #0f3a71;">Equipo Rifasys</strong><br>
                                <em style="font-size: 13px;">Innovación en Gestión de Sorteos</em>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 25px 30px; text-align: center;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: rgba(255,255,255,0.7);">
                                Este es un correo automatizado generado por el sistema Rifasys.<br>
                                Por favor, no respondas directamente a este mensaje.<br>
                                Si necesitas asistencia, utiliza los canales de soporte indicados arriba.
                            </p>
                            
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2);">
                                <p style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.5);">
                                    Contrato N° {{ $contract->contract_number }} | UUID: {{ $contract->uuid }}<br>
                                    © {{ date('Y') }} Rifasys - Publicidad en Red C.A. | Todos los derechos reservados
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>