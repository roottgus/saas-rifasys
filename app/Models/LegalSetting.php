<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id','titulo_terminos','terminos','titulo_politicas','politicas','titulo_devoluciones','devoluciones'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
