<?php

namespace App\Filament\Tenant\Widgets;


use Filament\Widgets\Widget;

class RifaLimitBanner extends Widget
{
    protected static string $view = 'filament.tenant.widgets.rifa-limit-banner';

public static function canView(): bool
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        if (!$tenant) return false;

        $rifasCount = \App\Models\Rifa::where('tenant_id', $tenant->id)->count();
        $limit = $tenant->rifas_limit;

        // Mostrar solo si el plan tiene límite y ya llegó al tope
        return !is_null($limit) && $rifasCount >= $limit;
    }

    protected function getViewData(): array
    {
        $tenant = \Filament\Facades\Filament::getTenant();

        return [
            'plan'       => $tenant->plan ?? '',
            'rifasLimit' => $tenant->rifas_limit,
            'rifasCount' => \App\Models\Rifa::where('tenant_id', $tenant->id)->count(),
        ];
    }
}
