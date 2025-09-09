@php
    $tenant = request()->route('tenant') instanceof \App\Models\Tenant ? request()->route('tenant') : null;
    $footer = $tenant?->footerSetting;
    $brandName = $footer->brand_name ?? $tenant?->name ?? ($brand->site_name ?? config('app.name'));
    $logoUrl = null;
    if ($footer?->logo_path) {
        $logoUrl = \Illuminate\Support\Facades\Storage::url($footer->logo_path);
    } elseif (isset($brand) && $brand?->logo_path) {
        $logoUrl = \Illuminate\Support\Facades\Storage::url($brand->logo_path);
    } elseif ($tenant?->logo_path) {
        $logoUrl = \Illuminate\Support\Facades\Storage::url($tenant->logo_path);
    }
    $slogan = $footer->description ?? 'Venta de Tikets Online';
    $email = $footer->email ?? $contact->email ?? null;
    $phone = $footer->phone ?? $contact->phone ?? null;
    $socials = $footer?->socials ? (is_array($footer->socials) ? $footer->socials : json_decode($footer->socials, true)) : [];
    $mainSocial = collect($socials)->first(fn($s) => !empty($s['url']) && !empty($s['icon']));
    $bg = $footer?->bg_color ?: '#2575e6';
    $text = $footer?->text_color ?: '#fff';
    $termsUrl = $footer->terms_url ?? null;
    $privacyUrl = $footer->privacy_url ?? null;
    $customHtml = $footer->custom_html ?? null;
    $normalize = function (?string $u) {
        if (!$u) return null;
        return preg_match('~^https?://~i', $u) ? $u : ('https://' . ltrim($u, '/'));
    };
    $termsUrl   = $normalize($termsUrl);
    $privacyUrl = $normalize($privacyUrl);
@endphp

<footer class="pt-7 pb-0 bg-[var(--primary)] border-t border-[#1B2945] text-white font-sans">
    <div class="max-w-3xl mx-auto px-4 py-2 flex flex-col md:flex-row md:items-start md:justify-between gap-5">

        {{-- Logo y nombre --}}
        <div class="flex flex-col items-center md:items-start gap-2 min-w-[120px]">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo {{ $brandName }}"
                     class="h-12 w-12 rounded-lg object-cover bg-white shadow border border-white/30 mb-1" />
            @endif
            <div class="text-base font-bold tracking-tight leading-tight text-white uppercase">{{ $brandName }}</div>
        </div>

        {{-- Contacto y Slogan --}}
        <div class="flex flex-col gap-1 items-center md:items-start w-full">
            <div class="font-medium text-white/80">{{ $slogan }}</div>
            <div class="flex items-center gap-2 text-sm text-white/90 mt-1">
                @if($email)
                    <i class="fa-solid fa-envelope text-white/70"></i>
                    <a href="mailto:{{ $email }}" class="hover:underline text-white/90">{{ $email }}</a>
                @endif
            </div>
            <div class="flex items-center gap-2 text-sm text-white/90">
                @if($phone)
                    <i class="fa-solid fa-phone text-white/70"></i>
                    <a href="tel:{{ $phone }}" class="hover:underline text-white/90">{{ $phone }}</a>
                @endif
            </div>
        </div>

        {{-- Enlaces legales y redes sociales --}}
        <div class="flex flex-col gap-1 items-center md:items-end w-full">
            <div class="flex gap-3 text-sm mt-2 md:mt-0">
                @if($termsUrl)
                    <a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer" class="text-white/90 hover:text-white underline font-semibold">Términos</a>
                @endif
                @if($privacyUrl)
                    <a href="{{ $privacyUrl }}" target="_blank" rel="noopener noreferrer" class="text-white/90 hover:text-white underline font-semibold">Privacidad</a>
                @endif
            </div>
            @if($mainSocial)
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-white/70 font-medium">Síguenos:</span>
                    <a href="{{ $normalize($mainSocial['url']) }}" target="_blank" rel="noopener noreferrer"
                       class="h-8 w-8 flex items-center justify-center rounded-full bg-white hover:bg-white/80 text-[var(--primary)] hover:text-[var(--primary)] transition border border-white/40 shadow">
                        <i class="{{ $mainSocial['icon'] }}"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-white/20 mt-3"></div>

    {{-- Info legal y autoría --}}
    <div class="max-w-3xl mx-auto px-4 py-2 flex flex-col md:flex-row items-center justify-between text-xs text-white/80 gap-2">
        <div>© {{ now()->year }} {{ $brandName }} — Todos los derechos reservados</div>
        <div class="flex items-center gap-2">
            <span>Desarrollado por</span>
            <a href="https://www.instagram.com/publienredca/" target="_blank" rel="noopener noreferrer"
               class="font-semibold hover:text-yellow-300 underline">PublienRed</a>
            <i class="fa-solid fa-building-columns text-yellow-300"></i>
        </div>
    </div>
</footer>
