<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (!isset($model->tenant_id) && app()->bound(Tenant::class)) {
                $model->tenant_id = app(Tenant::class)->id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $q) {
            if (app()->bound(Tenant::class)) {
                $q->where(
                    $q->getModel()->getTable().'.tenant_id',
                    app(Tenant::class)->id
                );
            }
        });
    }
}
