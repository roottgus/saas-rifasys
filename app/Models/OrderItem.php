<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id','order_id','rifa_id','numero','price'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function rifa(): BelongsTo { return $this->belongsTo(Rifa::class); }
}
