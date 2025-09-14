<?php

// app/Providers/Filament/TenantPanelProvider.php

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

// Middleware de solo lectura para demo
use App\Http\Middleware\DemoReadOnly;

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
            ->brandName(fn () => auth()->user()?->currentTenant?->name ?? '')


            // Autenticación
            ->login(Login::class)
            ->passwordReset()
            ->profile()

            // Colores
            ->colors([
                'primary' => Color::Indigo,
                'gray'    => Color::Slate,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
                'info'    => Color::Cyan,
            ])

            // Tema y Apariencia
            ->font('Inter')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('max-w-7xl')

            // Navegación
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

            // Descubrimiento
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\\Filament\\Tenant\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\\Filament\\Tenant\\Pages')

            // Pages
            ->pages([
                \App\Filament\Tenant\Pages\Dashboard::class,
            ])

            // Widgets
            ->widgets([
                \App\Filament\Tenant\Widgets\WelcomeWidget::class,
                \App\Filament\Tenant\Widgets\TenantStatsWidget::class,
                \App\Filament\Tenant\Widgets\RecentSalesWidget::class,
                \App\Filament\Tenant\Widgets\SalesChartWidget::class,
            ])

            // Menú de usuario
            ->userMenuItems([
                MenuItem::make()
                    ->label('Mi Cuenta')
                    ->url(fn (): string => '/panel/' . (\Filament\Facades\Filament::getTenant()?->slug ?? '') . '/profile')
                    ->icon('heroicon-o-user-circle'),
                MenuItem::make()
                    ->label('Configuración')
                    ->url(fn (): string => '/panel/' . (\Filament\Facades\Filament::getTenant()?->slug ?? '') . '/settings')
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

            // SPA & alerts
            ->spa()
            ->unsavedChangesAlerts()

            // =========================
            //  Estilos: Hooks correctos
            // =========================

            // 1) Estilos base para TODO el panel + estilos del recent-sales-widget + estilos del modal
            ->renderHook('panels::head.end', fn (): string =>
                view('filament.tenant.partials.styles')->render()
                . "\n"
                . '<style>' . view('filament.tenant.partials.styles.recent-sales-widget')->render() . '</style>'
                . "\n"
                . '<style>' . view('filament.tenant.partials.styles.recent-sales-modal')->render() . '</style>'
            )

            // 2) Estilos adicionales SOLO para la página Reports
            ->renderHook('panels::head.end', function (): string {
                if (request()->routeIs('filament.tenant.pages.reports')) {
                    // MUY IMPORTANTE: el partial debe ser CSS PURO (sin <style> dentro)
                    return '<style>' . view('filament.tenant.partials.styles.reports')->render() . '</style>';
                }
                return '';
            })

            // Scripts al final del body (evita interferir con Livewire/Chart.js)
            ->renderHook('panels::body.end', fn (): string => view('filament.tenant.partials.scripts')->render())

            // Banner "Modo Demo" para tenant_demo
            ->renderHook(
                'panels::content.start',
                fn (): string => (auth()->check() && auth()->user()->hasRole('tenant_demo'))
                    ? '<div class="mb-3 rounded-lg bg-amber-50 text-amber-800 px-4 py-2 text-sm border border-amber-200">
                           Estás en <strong>Modo Demo</strong>: solo lectura.
                       </div>'
                    : ''
            )

            
            // Footer
            ->renderHook('panels::footer', fn (): string => view('filament.tenant.partials.footer')->render())

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
                \App\Http\Middleware\SetTenant::class,
                DemoReadOnly::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}