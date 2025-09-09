<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tenant')
            ->path('panel')
            
            // Configuración de Tenant
            ->tenant(\App\Models\Tenant::class, slugAttribute: 'slug')
            
            // Marca dinámica del Tenant
            ->brandName(fn () => auth()->user()?->currentTenant?->name ?? 'Mi Panel')
            // ->favicon(asset('favicon.ico')) // Descomenta si tienes favicon
            
            // Autenticación
            ->login()
            ->passwordReset()
            ->profile()
            // ->registration() // Descomenta si necesitas registro de nuevos tenants
            
            // Colores modernos y vibrantes para clientes
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Cyan,
            ])
            
            // Tema y Apariencia
            ->font('Inter')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            
            // Grupos de navegación organizados para clientes
            ->navigationGroups([
                'Mi Negocio',
                'Rifas y Sorteos',
                'Participantes',
                'Ventas y Pagos',
                'Marketing',
                'Configuración',
            ])
            ->collapsibleNavigationGroups(true)
            
            // Breadcrumbs
            ->breadcrumbs(true)
            
            // Notificaciones
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            
            // Descubrimiento de recursos
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\\Filament\\Tenant\\Widgets')
            
            // Pages
            ->pages([
                Pages\Dashboard::class,
            ])
            
            // Widgets - Comenta los que no existan aún
            ->widgets([
                // Widget por defecto
                Widgets\AccountWidget::class,
                
                // Widgets personalizados - descomenta cuando los crees
                // \App\Filament\Tenant\Widgets\WelcomeWidget::class,
                // \App\Filament\Tenant\Widgets\TenantStatsWidget::class,
                // \App\Filament\Tenant\Widgets\RecentSalesWidget::class,
                // \App\Filament\Tenant\Widgets\SalesChartWidget::class,
            ])
            
            // Menú de usuario personalizado
            ->userMenuItems([
                MenuItem::make()
                    ->label('Mi Cuenta')
                    ->url(fn (): string => '/panel/' . (auth()->user()->currentTenant->slug ?? '') . '/profile')
                    ->icon('heroicon-o-user-circle'),
                MenuItem::make()
                    ->label('Configuración')
                    ->url(fn (): string => '/panel/' . (auth()->user()->currentTenant->slug ?? '') . '/settings')
                    ->icon('heroicon-o-cog-6-tooth'),
                MenuItem::make()
                    ->label('Centro de Ayuda')
                    ->url('/help')
                    ->icon('heroicon-o-question-mark-circle'),
                'logout' => MenuItem::make()
                    ->label('Cerrar Sesión'),
            ])
            
            // Búsqueda global
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            
            // SPA mode
            ->spa()
            
            // Alertas de cambios sin guardar
            ->unsavedChangesAlerts()
            
            // Estilos personalizados para el panel de clientes
            ->renderHook(
                'panels::head.start',
                fn (): string => '
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        :root {
                            --filament-sidebar-width: 280px;
                        }
                        
                        /* Tema moderno y amigable para clientes */
                        body {
                            font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif !important;
                        }
                        
                        /* Header del sidebar con gradiente */
                        .fi-sidebar-header {
                            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                            padding: 1.5rem;
                            margin: -1rem -1rem 1.5rem -1rem;
                            border-radius: 0 0 1rem 1rem;
                            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
                        }
                        
                        /* Logo del tenant */
                        .fi-sidebar-header .fi-avatar {
                            border: 3px solid rgba(255, 255, 255, 0.2);
                        }
                        
                        /* Sidebar con diseño moderno */
                        .fi-sidebar {
                            background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
                            border-right: 1px solid rgba(0,0,0,0.05);
                        }
                        
                        .dark .fi-sidebar {
                            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
                            border-right: 1px solid rgba(255,255,255,0.05);
                        }
                        
                        /* Items del menú con hover suave */
                        .fi-sidebar-item a {
                            margin: 0.25rem 0.75rem;
                            border-radius: 0.5rem;
                            transition: all 0.2s ease;
                        }
                        
                        .fi-sidebar-item a:hover {
                            background: rgba(99, 102, 241, 0.1);
                            transform: translateX(4px);
                        }
                        
                        /* Item activo con gradiente */
                        .fi-sidebar-item-active > a:first-child {
                            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                            color: white !important;
                            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
                        }
                        
                        .fi-sidebar-item-active a span, 
                        .fi-sidebar-item-active a svg {
                            color: white !important;
                        }
                        
                        /* Grupos de navegación */
                        .fi-sidebar-group {
                            margin-top: 1rem;
                        }
                        
                        .fi-sidebar-group-label {
                            font-weight: 600;
                            font-size: 0.75rem;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                            opacity: 0.6;
                            padding: 0 1rem;
                            margin-bottom: 0.5rem;
                        }
                        
                        /* Cards del dashboard con efecto glass */
                        .fi-wi-stats-overview-stat {
                            background: rgba(255, 255, 255, 0.9);
                            backdrop-filter: blur(10px);
                            border: 1px solid rgba(99, 102, 241, 0.1);
                            transition: all 0.3s ease;
                            border-radius: 1rem;
                            overflow: hidden;
                        }
                        
                        .dark .fi-wi-stats-overview-stat {
                            background: rgba(30, 41, 59, 0.9);
                            border: 1px solid rgba(99, 102, 241, 0.2);
                        }
                        
                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                            border-color: rgba(99, 102, 241, 0.3);
                        }
                        
                        /* Gráficos mejorados */
                        .fi-wi-chart {
                            border-radius: 1rem;
                            overflow: hidden;
                            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                        }
                        
                        /* Botones más atractivos */
                        .fi-btn {
                            border-radius: 0.5rem;
                            font-weight: 500;
                            transition: all 0.2s ease;
                            position: relative;
                            overflow: hidden;
                        }
                        
                        .fi-btn:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
                        }
                        
                        .fi-btn-primary {
                            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                        }
                        
                        /* Tablas con diseño limpio */
                        .fi-ta-table {
                            border-radius: 0.75rem;
                            overflow: hidden;
                        }
                        
                        .fi-ta-row {
                            transition: all 0.2s ease;
                        }
                        
                        .fi-ta-row:hover {
                            background: rgba(99, 102, 241, 0.05);
                        }
                        
                        /* Formularios más amigables */
                        .fi-fo-field-wrp {
                            margin-bottom: 1.25rem;
                        }
                        
                        input, textarea, select {
                            border-radius: 0.5rem !important;
                            transition: all 0.2s ease !important;
                        }
                        
                        input:focus, textarea:focus, select:focus {
                            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
                            border-color: #6366f1 !important;
                        }
                        
                        /* Notificaciones elegantes */
                        .fi-notification {
                            border-radius: 0.75rem;
                            backdrop-filter: blur(12px);
                            background: rgba(255,255,255,0.95);
                            border: 1px solid rgba(99, 102, 241, 0.2);
                            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                        }
                        
                        .dark .fi-notification {
                            background: rgba(30,41,59,0.95);
                            border: 1px solid rgba(99, 102, 241, 0.3);
                        }
                        
                        /* Badges con estilo */
                        .fi-badge {
                            font-weight: 600;
                            transition: all 0.2s ease;
                        }
                        
                        .fi-badge:hover {
                            transform: scale(1.05);
                        }
                        
                        /* Scrollbar elegante */
                        ::-webkit-scrollbar {
                            width: 8px;
                            height: 8px;
                        }
                        
                        ::-webkit-scrollbar-track {
                            background: #f1f5f9;
                            border-radius: 10px;
                        }
                        
                        ::-webkit-scrollbar-thumb {
                            background: linear-gradient(180deg, #6366f1, #8b5cf6);
                            border-radius: 10px;
                        }
                        
                        ::-webkit-scrollbar-thumb:hover {
                            background: linear-gradient(180deg, #4f46e5, #7c3aed);
                        }
                        
                        .dark ::-webkit-scrollbar-track {
                            background: #1e293b;
                        }
                        
                        /* Animaciones de entrada */
                        @keyframes slideIn {
                            from {
                                opacity: 0;
                                transform: translateY(10px);
                            }
                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                        
                        .fi-page-header, .fi-section {
                            animation: slideIn 0.3s ease-out;
                        }
                        
                        /* Widget de bienvenida personalizado */
                        .welcome-banner {
                            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                            color: white;
                            padding: 2rem;
                            border-radius: 1rem;
                            margin-bottom: 2rem;
                            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
                        }
                        
                        /* Mejoras para el panel header */
                        .fi-header {
                            backdrop-filter: blur(12px);
                            background: rgba(255, 255, 255, 0.8);
                            border-bottom: 1px solid rgba(0,0,0,0.05);
                        }
                        
                        .dark .fi-header {
                            background: rgba(30, 41, 59, 0.8);
                            border-bottom: 1px solid rgba(255,255,255,0.05);
                        }
                    </style>
                '
            )
            
            // Footer minimalista
            ->renderHook(
                'panels::footer',
                fn (): string => '
                    <div class="text-center py-6 text-gray-400 text-xs">
                        <p>
                            Powered by ' . config('app.name', 'Sistema de Rifas') . ' • 
                            <a href="/help" class="text-indigo-500 hover:text-indigo-600">Ayuda</a> • 
                            <a href="/terms" class="text-indigo-500 hover:text-indigo-600">Términos</a>
                        </p>
                    </div>
                '
            )
            
            // Middleware
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetTenant::class, // Middleware de tenant
            ])
            
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}