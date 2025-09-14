{{-- resources/views/filament/tenant/partials/styles/base.blade.php --}}

/* Variables globales */
:root {
    --filament-sidebar-width: 280px;
    --sidebar-bg: #ffffff;
    --sidebar-dark-bg: #1e293b;
    --primary-color: #00367C;
    --primary-hover: #002452;
    --accent-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --primary-50: 0 54 124; --primary-100: 0 54 124; --primary-200: 0 54 124;
    --primary-300: 0 54 124; --primary-400: 0 54 124; --primary-500: 0 54 124;
    --primary-600: 0 54 124; --primary-700: 0 54 124; --primary-800: 0 54 124;
    --primary-900: 0 54 124; --primary-950: 0 54 124;
}

/* Fuente base */
body { 
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif !important; 
}

/* HEADER PRINCIPAL */
.fi-header-heading {
    font-size: 2.35rem !important; 
    font-weight: 900 !important; 
    color: #00367C !important;
    letter-spacing: -0.025em !important; 
    margin-bottom: 0.7rem !important; 
    margin-top: 12px !important;
    text-shadow: 0 4px 18px rgba(0,54,124,0.08), 0 1px 0 #fff; 
    text-align: left !important; 
    line-height: 1.08 !important;
    display: flex !important; 
    align-items: center !important; 
    gap: 0.75rem !important; 
    background: none !important;
}

@media (max-width: 640px) { 
    .fi-header-heading { font-size: 1.18rem !important; } 
}

/* HEADER PRINCIPAL */
.fi-header { 
    backdrop-filter: blur(12px); 
    background: rgba(255, 255, 255, 0.8); 
    border-bottom: 1px solid rgba(0,0,0,0.05); 
}

.dark .fi-header { 
    background: rgba(30, 41, 59, 0.8); 
    border-bottom: 1px solid rgba(255,255,255,0.05); 
}



/* Mobile responsive base */
@media (max-width: 768px) {
    .fi-sidebar {
        box-shadow: 5px 0 25px rgba(0,0,0,0.1);
    }
}

/* CENTRADO DE CONTENIDO - ALTERNATIVA */
body.fi-panel-tenant {
    .fi-layout {
        padding-left: 260px; /* compensar el sidebar fijo */
    }
    
    .fi-main {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 3rem;
    }
}

/* Cuando el sidebar está colapsado */
body.fi-panel-tenant.fi-sidebar-collapsed {
    .fi-layout {
        padding-left: 0;
    }
}