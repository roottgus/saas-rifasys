<x-mail::message>
# ¡Contrato firmado exitosamente!

Hola {{ $contract->client_name }},

Tu contrato de servicio para la rifa **{{ $contract->raffle_name }}** ha sido firmado exitosamente el {{ $contract->signed_at->format('d/m/Y H:i') }}.

Adjunto a este correo encontrarás el contrato firmado en formato PDF.  
Guárdalo para tus archivos.

<x-mail::button :url="url('/mi-contrato')">
Ver mi contrato en Rifasys
</x-mail::button>

Si tienes alguna duda, puedes responder a este correo o contactarnos a [admin@publienred.com](mailto:info@publienred.com).

Gracias por confiar en Rifasys y Publienred.

Saludos cordiales,  
Equipo Rifasys & Publienred

</x-mail::message>
