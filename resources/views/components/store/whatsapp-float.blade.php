{{-- resources/views/components/store/whatsapp-float.blade.php --}}
@props(['contact' => null])

@php
    $enabled = ($contact->show_whatsapp_widget ?? false)
        && !empty($contact->whatsapp_phone ?? $contact->whatsapp_number ?? null);

    $raw   = $contact->whatsapp_phone ?? $contact->whatsapp_number ?? '';
    $phone = preg_replace('/\D+/', '', (string) $raw);
    $text  = urlencode($contact->whatsapp_message ?? 'Hola, tengo una consulta');
    $href  = ($enabled && $phone) ? "https://wa.me/{$phone}?text={$text}" : null;
@endphp

@if($href)
<a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
   class="group fixed bottom-5 right-5 z-[9999] inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-xl ring-1 ring-black/10 transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/60"
   aria-label="Contactar por WhatsApp">
    <svg viewBox="0 0 32 32" class="h-7 w-7" fill="currentColor" aria-hidden="true">
        <path d="M16 3a13 13 0 0 0-11 19.7L3.5 29 9 27.9A13 13 0 1 0 16 3Zm0 2a11 11 0 0 1 0 22c-1.9 0-3.7-.5-5.3-1.4l-.4-.2-3.2.7.7-3.2-.2-.4A11 11 0 0 1 16 5Zm-5.2 5.2c.3-.6.6-.6 1-.6h.8c.3 0 .6 0 .8.6s1 2 .9 2.2c0 .1-.1.3-.2.4l-.5.6c-.2.2-.4.4-.2.7.3.6 1.1 1.8 2.4 2.5 1.2.7 1.6.6 1.9.4l.6-.5c.2-.2.4-.2.6-.1l2 .9c.6.3 1 .5 1.1.8.1.3.1 1.6-.7 2.2-.8.6-2.3.9-3.9.5-1.9-.6-4.3-2-5.8-3.8-1.5-1.8-2.6-4.1-2.7-5.7 0-1 .3-1.8.9-2.6Z"/>
    </svg>
    <span class="pointer-events-none absolute right-16 origin-right select-none rounded-xl bg-black/80 px-3 py-1.5 text-sm text-white opacity-0 backdrop-blur transition-all group-hover:opacity-100">
        ¿Necesitas ayuda?
    </span>
</a>
@endif
