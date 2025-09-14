<?php

namespace App\Enums;

enum OrderStatus: string 
{
    case Pending   = 'pending';
    case Submitted = 'submitted';   // pago enviado / por verificar
    case Paid      = 'paid';        // pagado
    case Cancelled = 'cancelled';
    case Expired   = 'expired';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Pendiente',
            self::Submitted => 'Enviado',
            self::Paid      => 'Pagado',
            self::Cancelled => 'Cancelado',
            self::Expired   => 'Expirado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'warning',
            self::Submitted => 'info',
            self::Paid      => 'success',
            self::Cancelled => 'danger',
            self::Expired   => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Pending   => 'heroicon-o-clock',
            self::Submitted => 'heroicon-o-paper-airplane',
            self::Paid      => 'heroicon-o-check-circle',
            self::Cancelled => 'heroicon-o-x-circle',
            self::Expired   => 'heroicon-o-exclamation-circle',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn ($status) => [$status->value => $status->label()]
        )->toArray();
    }

    /** Conjunto de estados “comercialmente completados” para reportes */
    public static function completedStatuses(): array
    {
        return [self::Paid, self::Submitted];
    }

    /** Los mismos estados como strings (para SQL whereIn) */
    public static function completedValues(): array
    {
        return array_map(fn(self $c) => $c->value, self::completedStatuses());
    }

    public function isCompleted(): bool
    {
        return in_array($this, self::completedStatuses(), true);
    }
}
