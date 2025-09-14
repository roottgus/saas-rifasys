<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = 0;
    protected static string $view = 'filament.tenant.widgets.welcome';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $tenant = Filament::getTenant();

        // Calcula los valores, con null-safe y fallbacks
        $greeting   = $this->getGreeting();
        $userName   = $user->name ?? 'Usuario';
        $tenantName = $tenant->name ?? 'Mi Negocio';
        $tenantSlug = $tenant->slug ?? null;
        $plan       = $tenant->plan ?? 'Básico';
        $lastLogin  = $user->last_login_at?->diffForHumans() ?? 'Primera vez';
        $daysActive = $tenant->created_at?->diffInDays(now()) ?? 0;
        $tips       = $this->getAllTips(); // <-- Pasamos todos los tips

        return [
            'greeting'    => $greeting,
            'userName'    => $userName,
            'tenantName'  => $tenantName,
            'tenantSlug'  => $tenantSlug,
            'plan'        => $plan,
            'lastLogin'   => $lastLogin,
            'daysActive'  => $daysActive,
            'tips'        => $tips, // <-- Para JS dinámico en la vista
        ];
    }

    protected function getGreeting(): string
    {
        $hour = now()->hour;
        return match (true) {
            $hour < 12 => 'Buenos días',
            $hour < 19 => 'Buenas tardes',
            default    => 'Buenas noches',
        };
    }

    protected function getAllTips(): array
    {
        return [
            "Optimiza tus rifas compartiendo el enlace en redes sociales para alcanzar más participantes.",
            "Responde rápido a tus clientes para generar confianza y aumentar tus ventas.",
            "Recuerda actualizar tu banner y tus premios para mantener tu panel atractivo.",
            "Comparte los testimonios de tus ganadores para atraer nuevos participantes.",
            "Utiliza promociones temporales para incentivar la compra de más números.",
            "Verifica los pagos lo antes posible y marca las órdenes como pagadas.",
            "Revisa tus reportes y estadísticas para entender mejor el comportamiento de tus rifas.",
            "Personaliza tu panel con el logo y colores de tu marca para más profesionalismo.",
        ];
    }
}
