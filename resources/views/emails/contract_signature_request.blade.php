@component('mail::message')
# Solicitud de Firma de Contrato

Hola **{{ $contract->client_name }}**,

Has sido invitado a completar y firmar tu contrato de servicio con **Rifasys / Publienred**.

Haz clic en el siguiente botón para revisar, adjuntar tus documentos y firmar digitalmente tu contrato:

@component('mail::button', ['url' => $link])
Completar y Firmar Contrato
@endcomponent

Si tienes dudas, contáctanos a admin@publienred.com.

Gracias,<br>
El equipo de Rifasys / Publienred

---
*Este correo fue enviado automáticamente. No respondas a esta dirección.*
@endcomponent
