{{-- resources/views/filament/tenant/partials/styles/components.blade.php --}}

:root, .dark {
    --primary-50:   239,246,255;
    --primary-100:  219,234,254;
    --primary-200:  191,219,254;
    --primary-300:  147,197,253;
    --primary-400:  96,165,250;
    --primary-500:  59,130,246;
    --primary-600:  0,54,124; /* <-- Tu azul corporativo */
    --primary-700:  29,78,216;
    --primary-800:  30,64,175;
    --primary-900:  30,58,138;
}

.fi-header {
    background: transparent !important;
}

/* WELCOME BANNER */
.welcome-banner { 
    background: #00367C !important; 
    color: white; 
    padding: 2rem; 
    border-radius: 1rem; 
    margin-bottom: 2rem; 
    box-shadow: 0 10px 30px rgba(0, 54, 124, 0.25); 
    position: relative !important; /* Cambiado a importante */
    overflow: visible !important; /* Cambiado de hidden a visible */
    background: linear-gradient(135deg, #00367C 0%, #001F3F 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    transition: box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 54, 124, 0.1);
}

.welcome-banner:hover {
    box-shadow: 0 20px 40px rgba(0, 54, 124, 0.2);
}

/* Contenedor de botones flotantes */
.welcome-banner .action-buttons {
    position: absolute !important;
    top: 2rem !important;
    right: 2rem !important;
    z-index: 100 !important;
    display: flex !important;
    gap: 12px !important;
}

.welcome-banner::after {
    content: ""; 
    position: absolute; 
    bottom: -20%; 
    left: -20%; 
    width: 200%; 
    height: 200%;
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1), transparent 70%);
    transform: rotate(-25deg); 
    pointer-events: none;
    z-index: 1; /* Añadido z-index bajo */
}

.welcome-banner::before {
    content: ""; 
    position: absolute; 
    top: -50%; 
    right: -50%; 
    width: 200%; 
    height: 200%;
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.15), transparent 70%);
    transform: rotate(25deg); 
    pointer-events: none;
    z-index: 1; /* Añadido z-index bajo */
}

/* Asegurar que el contenido principal tenga el z-index correcto */
.welcome-banner > div {
    position: relative;
    z-index: 10;
}

/* Botón primario */
.btn-primary{
  display:inline-flex; align-items:center; gap:.5rem;
  padding:.5rem 1rem; border-radius:.5rem;
  box-sizing:border-box; white-space:nowrap;
  font-size:.95rem; line-height:1.2; font-weight:500;
  color:#fff; text-decoration:none; user-select:none; cursor:pointer;

  /* Fondo (elige UNA de las dos líneas de abajo) */
  background: rgb(var(--primary-600));                 /* opción sólida */
  /* background: linear-gradient(135deg, rgba(0,54,124,.8) 0%, rgba(0,36,82,.8) 100%); */ /* opción gradiente */

  border:1px solid rgba(255,255,255,.15);
  box-shadow:0 4px 12px rgba(0,54,124,.10);
  backdrop-filter:blur(6px);

  transition: background-color .2s, box-shadow .2s, transform .2s;
}
.btn-primary:hover{
  background: rgb(var(--primary-700));    /* si usas gradiente arriba, puedes mantener gradiente y sumar: filter:brightness(1.05); */
  box-shadow:0 6px 14px rgba(0,0,0,.12);
  transform:translateY(-1px);
}

/* Botón “glass” */
.btn-glass{
  display:inline-flex; align-items:center; gap:.5rem;
  padding:.5rem 1rem; border-radius:.5rem;
  color:#fff; text-decoration:none; user-select:none; cursor:pointer;

  background: rgba(255,255,255,.20);
  border:1px solid rgba(255,255,255,.20);
  box-shadow:0 2px 6px rgba(0,0,0,.08);
  backdrop-filter:blur(6px);

  transition: background-color .2s, box-shadow .2s, transform .2s;
}
.btn-glass:hover{
  background: rgba(255,255,255,.30);
  box-shadow:0 6px 14px rgba(0,0,0,.12);
  transform:translateY(-1px);
}

/* Accesibilidad (opcional) */
.btn-primary:focus-visible,
.btn-glass:focus-visible{
  outline:2px solid rgba(59,130,246,.6); /* aprox primary-500 */
  outline-offset:2px;
}


  /* Estado deshabilitado */
  .btn-disabled{
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.5rem 1rem; border-radius:.5rem;
    color: rgba(255,255,255,.5);
    background: rgba(255,255,255,.10);
    backdrop-filter: blur(6px);
    cursor:not-allowed;
  }

  

@media (max-width: 640px) { 
    .welcome-banner { text-align: center; } 
}

/* STATS Y WIDGETS */
.fi-wi-stats-overview-stat { 
    background: rgba(255, 255, 255, 0.93); 
    backdrop-filter: blur(10px); 
    border: 1px solid rgba(0, 54, 124, 0.09); 
    border-radius: 1rem; 
    overflow: hidden; 
}

.fi-wi-stats-overview-stat:hover { 
    box-shadow: 0 20px 40px rgba(0,54,124,0.11); 
    border-color: rgba(0,54,124,0.18); 
}

.fi-wi-chart { 
    border-radius: 1rem; 
    overflow: hidden; 
    box-shadow: 0 4px 6px rgba(0,54,124,0.07); 
}




/* BOTONES */
.fi-btn-primary { 
    background: #00367C !important; 
    border: none !important;

}

.fi-btn-primary:hover { 
    background: #002452 !important; 
}

/* BADGES GENERALES */
.fi-badge { 
    background: #00367C !important; 
    color: #fff !important; 
    font-weight: 600; 
}

.fi-badge:hover { 
    transform: scale(1.05); 
}

/* NOTIFICACIONES */
.fi-notification { 
    border-radius: 0.75rem; 
    background: rgba(255,255,255,0.97); 
    border: 1px solid rgba(0,54,124,0.14); 
    box-shadow: 0 10px 25px rgba(0,54,124,0.07); 
}

.dark .fi-notification { 
    background: rgba(30,41,59,0.97); 
    border: 1px solid rgba(0,54,124,0.13); 
}

/* TABLAS */
.fi-ta-table { 
    border-radius: 0.75rem; 
    overflow: hidden; 
}

.fi-ta-row { 
    transition: all 0.2s ease; 
}

.fi-ta-row:hover { 
    background: rgba(0, 54, 124, 0.05); 
}

/* FORMULARIOS */
.fi-fo-field-wrp { 
    margin-bottom: 1.25rem; 
}

input, textarea, select { 
    border-radius: 0.5rem !important; 
    transition: all 0.2s ease !important; 
}

input:focus, textarea:focus, select:focus { 
    box-shadow: 0 0 0 3px rgba(0, 54, 124, 0.07) !important; 
    border-color: #00367C !important;