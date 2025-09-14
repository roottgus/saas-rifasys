/* ================================
   FILAMENT: Recent Sales Modal PRO
   ================================ */

.modal-blur-bg {
    background: rgba(32,36,52,0.22);
    backdrop-filter: blur(4px);
}

.recent-sales-modal {
    border-radius: 1.5rem;
    box-shadow: 0 12px 48px rgba(48, 62, 105, 0.19), 0 2px 8px rgba(34, 46, 80, 0.11);
    border: 1px solid #e7eaf5;
    background: #fff;
    max-width: 480px;
    width: 100%;
    margin: 0 auto;
    overflow: hidden;
    animation: fadein-up 0.32s cubic-bezier(.23,1,.32,1);
}
.dark .recent-sales-modal {
    background: #181e2f;
    border-color: #23284c;
    box-shadow: 0 18px 54px rgba(24,28,55,0.23), 0 3px 12px rgba(32,46,80,0.13);
}

.recent-sales-modal-header {
    padding: 1.2rem 2rem 1rem 2rem;
    background: #00367C;
    color: #fff;
    border-bottom: 1px solid #e7eaf5;
    
}
.dark .recent-sales-modal-header {
    background: linear-gradient(90deg,#199865 5%,#3066b2 50%,#4446a1 100%);
    border-bottom: 1px solid #23284c;
}

.recent-sales-modal-close {
    color: #fff;
    opacity: 0.85;
    transition: opacity 0.13s;
    border-radius: 9999px;
    background: rgba(255,255,255,0.12);
    padding: 0.28rem 0.38rem;
    margin-left: 8px;
}
.recent-sales-modal-close:hover {
    opacity: 1;
    background: rgba(255,255,255,0.18);
}

.recent-sales-modal-body {
    padding: 2rem 2rem 1.3rem 2rem;
    background: linear-gradient(180deg,#fff 66%,#f4f6fb 100%);
}
.dark .recent-sales-modal-body {
    background: linear-gradient(180deg,#1a2035 66%,#1b1d31 100%);
}

.recent-sales-modal-block {
    border-radius: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(22,28,65,0.06);
    border: 1px solid #e7eaf5;
    background: #f9fafb;
    padding: 1.25rem 1.1rem 1.1rem 1.25rem;
}
.dark .recent-sales-modal-block {
    background: #21243a;
    border-color: #2c3150;
}

.recent-sales-modal-block:last-child { margin-bottom: 0; }

.recent-sales-modal-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #334155;
    gap: 0.45em;
    font-size: 1rem;
    margin-bottom: 0.6rem;
}
.dark .recent-sales-modal-label { color: #b7cdfd; }

.recent-sales-modal-footer {
    background: #f6f9fd;
    border-top: 1px solid #e7eaf5;
    padding: 1rem 2rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
}
.dark .recent-sales-modal-footer {
    background: #16192b;
    border-top: 1px solid #23284c;
}

/* Botón verde aprobar */
.recent-sales-modal .approve-btn {
    background: #15C77F;    /* Verde profesional */
    color: #fff;
    border: 0;
    border-radius: 0.32em;
    font-weight: 600;
    box-shadow: none;
    padding: 0.48em 1em;
    font-size: 0.98em;
    min-width: 102px;
    height: 2.25em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4em;
    letter-spacing: 0.01em;
    transition: background 0.16s, color 0.13s;
}
.recent-sales-modal .approve-btn:hover,
.recent-sales-modal .approve-btn:focus {
    background: #12a36b;
    color: #fff;
}

/* Animación extra */
@keyframes fadein-up {
    from { opacity: 0; transform: translateY(44px);}
    to   { opacity: 1; transform: none;}
}

/* --- MODAL APROBACIÓN 2 COLUMNAS --- */
.rs-modal-flex {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
@media (min-width: 768px) {
    .rs-modal-flex {
        flex-direction: row;
        align-items: flex-start !important;
        gap: 2.5rem;
    }
    .rs-modal-left {
        flex: 1 1 0%;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .rs-modal-right {
        width: 260px;
        max-width: 100%;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
}
/* Controla el tamaño de la imagen del comprobante para nunca ser gigante */
.rs-voucher-img {
    max-width: 100%;
    width: 100%;
    max-height: 210px;
    object-fit: contain;
    background: #f3f4f6;
    border-radius: 0.75rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.06);
    cursor: pointer;
    transition: opacity 0.2s;
}
.rs-voucher-img:hover {
    opacity: 0.93;
}

.recent-sales-modal .reject-btn {
    background: #f43f5e;
    color: #fff;
    border: none;
    border-radius: 0.32em;
    font-weight: 600;
    padding: 0.48em 1em;
    font-size: 0.98em;
    min-width: 120px;
    height: 2.25em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4em;
    transition: background 0.13s, color 0.13s;
}
.recent-sales-modal .reject-btn:hover,
.recent-sales-modal .reject-btn:focus {
    background: #dc2626;
    color: #fff;
}
