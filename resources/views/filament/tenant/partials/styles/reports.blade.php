/* ESTILOS CORREGIDOS PARA PÁGINA DE REPORTES - ANCHO CONSISTENTE */


/* Eliminar padding del contenedor principal de Filament */
.fi-page-simple .fi-simple-main {
    max-width: none !important;
}

/* Container principal sin padding extra */
.reports-analytics-container {
    padding: 0;
    max-width: 100%;
    margin: 0;
    background: transparent;
}

.dark .reports-analytics-container {
    background: transparent;
}

/* Header Analytics - Mismo estilo que el dashboard */
.analytics-header {
    background: linear-gradient(135deg, #00367C 40%, #144586 100%);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 54, 124, 0.15);
    position: relative;
    overflow: hidden;
}

.analytics-header::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    pointer-events: none;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
}

.header-info {
    color: white;
}

.analytics-title {
    font-size: 1.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.title-icon {
    width: 28px;
    height: 28px;
}

.analytics-subtitle {
    font-size: 0.9375rem;
    opacity: 0.9;
    margin-top: 0.25rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-export,
.btn-refresh {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-export {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-export:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.btn-refresh {
    background: white;
    color: #00367C;
}

.btn-refresh:hover {
    transform: rotate(180deg);
}

/* Filters Bar */
.filters-bar {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
    min-width: 150px;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-select,
.filter-input {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.95);
    color: #1f2937;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
}

/* KPIs Grid - Mismo estilo que dashboard */
.kpis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.kpi-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-height: 120px;
}

.dark .kpi-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 54, 124, 0.1);
}

.kpi-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.kpi-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kpi-revenue .kpi-icon-wrapper {
    background: rgba(16, 185, 129, 0.1);
}

.kpi-orders .kpi-icon-wrapper {
    background: rgba(59, 130, 246, 0.1);
}

.kpi-ticket .kpi-icon-wrapper {
    background: rgba(245, 158, 11, 0.1);
}

.kpi-participants .kpi-icon-wrapper {
    background: rgba(139, 92, 246, 0.1);
}

.kpi-icon {
    width: 20px;
    height: 20px;
}

.kpi-revenue .kpi-icon { color: #10b981; }
.kpi-orders .kpi-icon { color: #3b82f6; }
.kpi-ticket .kpi-icon { color: #f59e0b; }
.kpi-participants .kpi-icon { color: #8b5cf6; }

.kpi-label {
    font-size: 0.8125rem;
    color: #6b7280;
    font-weight: 500;
}

.dark .kpi-label {
    color: #9ca3af;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.dark .kpi-value {
    color: #f3f4f6;
}

.kpi-change {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.kpi-change.positive {
    color: #10b981;
}

.kpi-change.negative {
    color: #ef4444;
}

.change-icon {
    width: 14px;
    height: 14px;
}

.change-label {
    font-size: 0.6875rem;
    color: #9ca3af;
    font-weight: 400;
}

.kpi-sparkline {
    position: absolute;
    bottom: 0.75rem;
    right: 0.75rem;
    width: 60px;
    height: 30px;
    opacity: 0.2;
}

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.chart-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
    min-height: 300px;
}

.dark .chart-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
}

.chart-full {
    grid-column: span 2;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.dark .chart-title {
    color: #f3f4f6;
}

.chart-actions {
    display: flex;
    gap: 0.375rem;
}

.chart-btn {
    padding: 0.25rem 0.625rem;
    border-radius: 0.25rem;
    font-size: 0.6875rem;
    font-weight: 500;
    background: transparent;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    transition: all 0.2s;
}

.chart-btn.active,
.chart-btn:hover {
    background: #00367C;
    color: white;
    border-color: #00367C;
}

.chart-body {
    height: 220px;
    position: relative;
}

.chart-legend {
    display: flex;
    gap: 1rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e5e7eb;
    font-size: 0.75rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: #6b7280;
}

.legend-color {
    width: 10px;
    height: 10px;
    border-radius: 2px;
}

/* Tables */
.tables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.table-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dark .table-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.table-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.dark .table-title {
    color: #f3f4f6;
}

.table-badge {
    padding: 0.1875rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.6875rem;
    font-weight: 600;
    background: linear-gradient(135deg, #00367C 0%, #0052CC 100%);
    color: white;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.data-table thead {
    background: #f8fafc;
}

.dark .data-table thead {
    background: #334155;
}

.data-table thead th {
    padding: 0.625rem 0.75rem;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
}

.dark .data-table thead th {
    color: #94a3b8;
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

.data-table tbody tr {
    transition: all 0.2s;
    border-bottom: 1px solid #f1f5f9;
}

.dark .data-table tbody tr {
    border-bottom-color: rgba(255, 255, 255, 0.05);
}

.data-table tbody tr:hover {
    background: rgba(0, 54, 124, 0.02);
}

.dark .data-table tbody tr:hover {
    background: rgba(0, 82, 204, 0.05);
}

.data-table tbody td {
    padding: 0.75rem;
    font-size: 0.8125rem;
    color: #1f2937;
}

.dark .data-table tbody td {
    color: #f3f4f6;
}

.table-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.item-rank {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00367C 0%, #0052CC 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
    font-weight: 700;
}

.item-name {
    font-weight: 500;
    color: #1f2937;
    font-size: 0.8125rem;
}

.dark .item-name {
    color: #f3f4f6;
}

.item-email {
    display: block;
    font-size: 0.6875rem;
    color: #9ca3af;
    margin-top: 0.125rem;
}

.text-success {
    color: #10b981;
    font-weight: 600;
}

/* Insights Section */
.insights-section {
    margin-top: 1.5rem;
}

.insights-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.dark .insights-title {
    color: #f3f4f6;
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

.insight-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.25rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.dark .insight-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
}

.insight-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 54, 124, 0.08);
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 0.5rem;
    background: rgba(0, 54, 124, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.insight-icon svg {
    width: 20px;
    height: 20px;
    color: #00367C;
}

.insight-content h4 {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 0.375rem;
}

.dark .insight-content h4 {
    color: #94a3b8;
}

.insight-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #00367C;
    margin-bottom: 0.1875rem;
}

.dark .insight-value {
    color: #60a5fa;
}

.insight-detail {
    font-size: 0.75rem;
    color: #9ca3af;
}

/* Responsive */
@media (max-width: 1280px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-full {
        grid-column: span 1;
    }
    
    .tables-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .filters-bar {
        flex-direction: column;
    }
    
    .kpis-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .tables-grid {
        grid-template-columns: 1fr;
    }
}

/* Corrige las flechas repetidas en los selects personalizados */
.filter-select {
    background-image: none !important;   /* quita cualquier background custom */
    appearance: auto !important;         /* usa la flecha nativa */
    -webkit-appearance: auto !important;
    -moz-appearance: auto !important;
    padding-right: 2.2rem !important;    /* espacio para la flecha estándar */
}
