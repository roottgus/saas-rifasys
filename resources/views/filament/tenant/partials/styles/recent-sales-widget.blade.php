/* ================================
   FILAMENT: Recent Sales Widget PRO
   ================================ */

/* Tabla y Sección General */
.recent-sales-table {
    border-radius: 1.25rem;
    box-shadow: 0 6px 28px rgba(34, 46, 80, 0.10), 0 1.5px 6px rgba(34, 46, 80, 0.06);
    overflow: hidden;
    background: #fff;
}
.dark .recent-sales-table {
    background: #0a1024;
    box-shadow: 0 8px 32px rgba(18,24,42,0.20), 0 2px 8px rgba(24,28,42,0.07);
}

/* Cabecera */
.recent-sales-table thead tr {
    background: linear-gradient(90deg, #f1f5ff 40%, #e7eeff 100%);
    border-bottom: 2px solid #e2e8f0;
}
.dark .recent-sales-table thead tr {
    background: linear-gradient(90deg, #212952 40%, #151b31 100%);
    border-bottom: 2px solid #23284c;
}
.recent-sales-table th {
    padding: 1rem 0.75rem;
    font-weight: 700;
    color: #173476;
    letter-spacing: 0.01em;
    background: none;
}
.dark .recent-sales-table th {
    color: #c7d5fd;
}

/* Filas */
.recent-sales-table tbody tr {
    transition: background 0.14s;
    border-bottom: 1px solid #f0f4f8;
}
.dark .recent-sales-table tbody tr {
    border-bottom: 1px solid #22253a;
}
.recent-sales-table tbody tr:hover {
    background: #f0f7ff;
}
.dark .recent-sales-table tbody tr:hover {
    background: #192040;
}
.recent-sales-table tbody tr:nth-child(even) {
    background: #f6f8fb;
}
.dark .recent-sales-table tbody tr:nth-child(even) {
    background: #14192f;
}

/* Celdas */
.recent-sales-table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    font-size: 0.85rem;
    color: #394867;
    font-weight: 500;
    background: none;
    white-space: nowrap;
    max-width: 0; /* para truncar texto largo */
    text-overflow: ellipsis;
    
}

/* Código - monoespaciado */
.recent-sales-table .order-code {
    font-family: 'JetBrains Mono', 'Menlo', 'Consolas', monospace;
    font-weight: 600;
    color: #3139b4;
    letter-spacing: 0.04em;
}
.dark .recent-sales-table .order-code {
    color: #8caaff;
}

/* Estado Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.92em;
    padding: 0.38em 0.90em 0.38em 0.75em;
    gap: 0.36em;
    background: #f6f6fa;
    color: #2d3e63;
    transition: all 0.16s;
    box-shadow: 0 2px 6px rgba(40,68,140,0.06);
}
.status-badge[data-status="pending"]   { background: #fffbe9; color: #b3860b; }
.status-badge[data-status="submitted"] { background: #e9f4ff; color: #2278bb; }
.status-badge[data-status="paid"]      { background: #e9fbe9; color: #14802b; }
.status-badge[data-status="cancelled"] { background: #f7f7f9; color: #6c7283; }
.status-badge[data-status="expired"]   { background: #ffe9e9; color: #bb2222; }
.dark .status-badge[data-status="pending"]   { background: #2e2308; color: #ffe08a; }
.dark .status-badge[data-status="submitted"] { background: #132133; color: #6db4ff; }
.dark .status-badge[data-status="paid"]      { background: #0f2613; color: #45ea91; }
.dark .status-badge[data-status="cancelled"] { background: #232233; color: #bdc7db; }
.dark .status-badge[data-status="expired"]   { background: #291a1a; color: #ff8383; }

/* Acciones */
.recent-sales-table .actions-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.44em 1.15em;
    border-radius: 0.6em;
    background: #f2f6ff;
    color: #1d3778;
    font-weight: 500;
    font-size: 0.92em;
    border: 1px solid #dde7fa;
    transition: background 0.18s, color 0.14s, box-shadow 0.12s;
    gap: 0.33em;
}

/* --- BOTÓN APROBAR PAGO VERDE --- */
.recent-sales-table .actions-btn.approve-btn {
    background: #e7faec;
    color: #168a3b;
    border: 1px solid #a5f0c4;
    font-weight: 600;
    
}

.recent-sales-table .actions-btn.approve-btn:hover {
    background: #d0f6de;
    color: #0e7030;
    box-shadow: 0 2px 8px rgba(16,144,86,0.08);
}

.dark .recent-sales-table .actions-btn.approve-btn {
    background: #163824;
    color: #5afc8d;
    border: 1px solid #1b4c2f;
}
.dark .recent-sales-table .actions-btn.approve-btn:hover {
    background: #11562c;
    color: #eafff4;
}
/* --- FIN BOTÓN APROBAR PAGO VERDE --- */

.recent-sales-table .actions-btn:hover {
    background: #e2ebff;
    color: #2b46a3;
    box-shadow: 0 2px 8px rgba(0,54,124,0.07);
}
.dark .recent-sales-table .actions-btn {
    background: #18203a;
    color: #a1bbff;
    border: 1px solid #23326a;
}
.dark .recent-sales-table .actions-btn:hover {
    background: #1e264b;
    color: #fff;
}

/* Placeholder vacío */
.recent-sales-table .empty-state {
    padding: 2.2em 0;
    text-align: center;
    color: #8ba1ca;
    font-weight: 600;
    letter-spacing: 0.04em;
}
.dark .recent-sales-table .empty-state {
    color: #a5b9df;
}

/* Modal animaciones extra */
@keyframes fadein-up {
  from { opacity: 0; transform: translateY(36px);}
  to { opacity: 1; transform: none;}
}
.animate-fadein-up { animation: fadein-up 0.35s cubic-bezier(.19,1,.22,1);}


