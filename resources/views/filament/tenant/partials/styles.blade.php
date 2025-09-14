{{-- resources/views/filament/tenant/partials/styles.blade.php --}}

{{-- Fuentes --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    {{-- Variables CSS y estilos base --}}
    @include('filament.tenant.partials.styles.base')
    
    {{-- Estilos del sidebar y navegación --}}
    @include('filament.tenant.partials.styles.sidebar')
    
    {{-- Animaciones globales --}}
    @include('filament.tenant.partials.styles.animations')
    
    {{-- Componentes UI (botones, badges, forms, etc) --}}
    @include('filament.tenant.partials.styles.components')
    
    {{-- Estilos específicos de la página de reportes --}}
    @include('filament.tenant.partials.styles.reports')

    
</style>