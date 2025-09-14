<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Auth\Login;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            // Marca
            ->brandName(config('app.name', 'Sistema de Rifas'))
            // ->brandLogo(asset('images/logo.svg'))
            // ->favicon(asset('favicon.ico'))

            // Autenticación
            ->login(Login::class)
            ->passwordReset()
            ->profile()
            // ->registration()
            // ->emailVerification()

            // Colores
            ->colors([
                'primary' => Color::Blue,
                'gray'    => Color::Gray,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
                'info'    => Color::Sky,
            ])

            // Tema
            ->font('Inter')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)

            // Navegación
            ->navigationGroups([
                'Dashboard',
                'Gestión de Rifas',
                'Participantes',
                'Configuración',
                'Reportes',
                // (Opcional) Seguridad / Roles y permisos aparecerán por Shield en este panel.
            ])
            ->collapsibleNavigationGroups(true)

            // Breadcrumbs
            ->breadcrumbs(true)

            // Notificaciones
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')

            // Descubrimiento
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')

            // Pages
            ->pages([
                Pages\Dashboard::class,
            ])

            // Widgets
            ->widgets([
                Widgets\AccountWidget::class,
                // \App\Filament\Widgets\StatsOverviewWidget::class,
                // \App\Filament\Widgets\RecentActivityWidget::class,
                // \App\Filament\Widgets\SalesChartWidget::class,
            ])

            // Menú usuario
            ->userMenuItems([
                MenuItem::make()
                    ->label('Mi Perfil')
                    ->url('/admin/profile')
                    ->icon('heroicon-o-user-circle'),
                'logout' => MenuItem::make()->label('Cerrar Sesión'),
            ])

            // Búsqueda
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            // SPA & alerts
            ->spa()
            ->unsavedChangesAlerts()

            // Estilos
            ->renderHook(
                'panels::head.start',
                fn (): string => '
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        :root { --filament-sidebar-width: 270px; }
                        body { font-family: "Inter", sans-serif !important; }
                        .fi-sidebar-header {
                            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                            padding: 1.5rem; margin: -1rem -1rem 1rem -1rem; border-radius: 0 0 1rem 1rem;
                        }
                        .fi-wi-stats-overview-stat {
                            transition: all .3s cubic-bezier(.4,0,.2,1);
                            border: 1px solid rgba(0,0,0,.05); position: relative; overflow: hidden;
                        }
                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 20px 40px -15px rgba(0,0,0,.15);
                        }
                        .fi-sidebar { background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%); }
                        .dark .fi-sidebar { background: linear-gradient(180deg, #1f2937 0%, #111827 100%); }
                        .fi-sidebar-item a { transition: all .2s ease; position: relative; }
                        .fi-sidebar-item a:hover { transform: translateX(4px); }
                        .fi-btn { transition: all .2s cubic-bezier(.4,0,.2,1); position: relative; overflow: hidden; }
                        .fi-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 16px -4px rgba(0,0,0,.2); }
                        .fi-ta-row { transition: all .2s ease; }
                        .fi-ta-row:hover { background-color: rgba(59, 130, 246, .05); }
                        .fi-section { box-shadow: 0 1px 3px rgba(0,0,0,.1); transition: box-shadow .3s ease; }
                        .fi-section:hover { box-shadow: 0 10px 25px rgba(0,0,0,.1); }
                        .fi-loading-indicator {
                            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 25%, #ec4899 50%, #8b5cf6 75%, #3b82f6 100%);
                            background-size: 200% 100%; animation: shimmer 2s infinite;
                        }
                        @keyframes shimmer { 0% {background-position: 200% 0;} 100% {background-position: -200% 0;} }
                        .fi-notification {
                            backdrop-filter: blur(16px) saturate(180%);
                            background: rgba(255,255,255,.85);
                            border: 1px solid rgba(255,255,255,.3);
                            box-shadow: 0 8px 32px rgba(0,0,0,.1);
                        }
                        .dark .fi-notification { background: rgba(31,41,55,.85); border: 1px solid rgba(255,255,255,.1); }
                        input:focus, textarea:focus, select:focus {
                            box-shadow: 0 0 0 3px rgba(59,130,246,.1) !important; border-color: rgb(59,130,246) !important;
                        }
                        .fi-badge { transition: all .2s ease; }
                        .fi-badge:hover { transform: scale(1.05); }
                        ::-webkit-scrollbar { width: 8px; height: 8px; }
                        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
                        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #3b82f6, #8b5cf6); border-radius: 10px; }
                        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #2563eb, #7c3aed); }
                        .dark ::-webkit-scrollbar-track { background: #1f2937; }
                        .fi-modal-window { box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); }
                        .fi-tabs-tab { transition: all .2s ease; }
                        .fi-tabs-tab:hover { background-color: rgba(59,130,246,.1); }
                        .fi-global-search-field input::placeholder { content: "Buscar... (Ctrl+K)"; }
                    </style>
                '
            )

            // Footer
            ->renderHook(
                'panels::footer',
                fn (): string => '
                    <div class="text-center py-8 text-gray-500 text-sm">
                        <p class="mb-2">
                            © ' . date('Y') . ' ' . config('app.name', 'Sistema de Rifas') . '
                             Todos los derechos reservados
                        </p>
                        <p class="space-x-4">
                            <span class="text-gray-400">v1.0.0</span>
                            <span>•</span>
                            <a href="#" class="text-blue-500 hover:text-blue-600 transition-colors">Soporte</a>
                        </p>
                    </div>
                '
            )

            // Middleware (sin SetTenant ni DemoReadOnly aquí)
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
            ])

            // Plugins
            ->plugins([
                // En ADMIN sí mostramos navegación de Shield para gestionar Roles/Permisos
                FilamentShieldPlugin::make(),
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
