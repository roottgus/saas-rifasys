<?php

namespace App\Services;

use App\Models\PaymentAccount;
use App\Models\Tenant;
use Illuminate\Support\Collection;

class PaymentAccountService
{
    /**
     * Prepara las cuentas de pago para el frontend
     */
    public function prepareAccountsForCheckout(Collection $accounts, ?Tenant $tenant = null): Collection
    {
        $tenantRate = $tenant?->tasa_bs ? (float)$tenant->tasa_bs : null;

        return $accounts->map(function (PaymentAccount $account) use ($tenantRate) {
            return $this->formatAccountForFrontend($account, $tenantRate);
        })->values();
    }

    /**
     * Formatea una cuenta individual para el frontend
     */
    public function formatAccountForFrontend(PaymentAccount $account, ?float $tenantRate = null): array
    {
        // 1) Flags nuevos (si están hidratados) o fallback a 'monedas' legacy
        $usdRaw = $account->getRawOriginal('usd_enabled');
        $bsRaw  = $account->getRawOriginal('bs_enabled');

        // Legacy: normaliza 'monedas'
        $mon = $account->monedas ?? [];
        if (is_string($mon)) {
            $tmp = json_decode($mon, true);
            $mon = is_array($tmp) ? $tmp : [];
        }
        $mon = is_array($mon) ? array_map('strtolower', $mon) : [];

        $usdEnabled = !is_null($usdRaw)
            ? ((int)$usdRaw === 1)
            : (in_array('usd', $mon, true) || in_array('dolares', $mon, true));

        $bsEnabled = !is_null($bsRaw)
            ? ((int)$bsRaw === 1)
            : (in_array('ves', $mon, true) || in_array('bs', $mon, true) || in_array('bolivar', $mon, true));

        // 2) Tasa: prioriza por cuenta; si no hay, usa la del tenant
        $tasa = $account->getRawOriginal('tasa_bs');
        $tasa = is_numeric($tasa) ? (float)$tasa : (is_numeric($tenantRate) ? (float)$tenantRate : null);

        return [
            'id'               => $account->id,
            'logo'             => $account->logo ? asset('storage/' . $account->logo) : null,
            'etiqueta'         => $account->etiqueta,
            'banco'            => $account->banco,
            'numero'           => $account->numero,
            'iban'             => $account->iban,
            'titular'          => $account->titular,
            'documento'        => $account->documento,
            'email'            => $account->email,
            'wallet'           => $account->wallet,
            'red'              => $account->red,
            'notes'            => $account->notes,
            'requiere_voucher' => (bool) $account->requiere_voucher,

            'usd_enabled'      => $usdEnabled,
            'bs_enabled'       => $bsEnabled,
            'tasa_bs'          => $tasa,
            'can_bs'           => $bsEnabled && is_numeric($tasa) && $tasa > 0,

            'accepted_currencies' => array_values(array_filter([
                $usdEnabled ? 'USD' : null,
                ($bsEnabled && is_numeric($tasa) && $tasa > 0) ? 'VES' : null,
            ])),
        ];
    }

    /**
     * Encuentra la mejor cuenta para el checkout (usa MODELOS, no arrays)
     */
    public function findBestAccountForCheckout(Collection $accounts): ?PaymentAccount
    {
        // Asegúrate de que los modelos tengan los campos hidratados y/o usa casts en el modelo.
        $bsAccount = $accounts->first(function (PaymentAccount $acc) {
            $bs = $acc->getRawOriginal('bs_enabled');
            $tasa = $acc->getRawOriginal('tasa_bs');

            $bsEnabled = !is_null($bs) && ((int)$bs === 1);
            $tasaOk = is_numeric($tasa) && (float)$tasa > 0;

            return $bsEnabled && $tasaOk;
        });

        if ($bsAccount) return $bsAccount;

        $usdAccount = $accounts->first(function (PaymentAccount $acc) {
            $usd = $acc->getRawOriginal('usd_enabled');
            return !is_null($usd) && ((int)$usd === 1);
        });

        return $usdAccount ?: $accounts->first();
    }

    /**
     * Valida si una cuenta puede procesar un pago
     */
    public function validateAccountForPayment(PaymentAccount $account, string $currency, float $amount): array
    {
        $errors = [];

        if (!$account->activo) {
            $errors[] = 'Esta cuenta de pago no está activa.';
        }

        if ($currency === 'VES') {
            $bs = $account->getRawOriginal('bs_enabled');
            $tasa = $account->getRawOriginal('tasa_bs');

            $bsEnabled = !is_null($bs) && ((int)$bs === 1);
            $tasaOk = is_numeric($tasa) && (float)$tasa > 0;

            if (!$bsEnabled) {
                $errors[] = 'Esta cuenta no acepta pagos en Bolívares.';
            } elseif (!$tasaOk) {
                $errors[] = 'Esta cuenta no tiene configurada una tasa de cambio válida.';
            }
        }

        if ($currency === 'USD') {
            $usd = $account->getRawOriginal('usd_enabled');
            $usdEnabled = !is_null($usd) && ((int)$usd === 1);
            if (!$usdEnabled) {
                $errors[] = 'Esta cuenta no acepta pagos en Dólares.';
            }
        }

        if ($amount <= 0) {
            $errors[] = 'El monto debe ser mayor a cero.';
        }

        return $errors;
    }
}
