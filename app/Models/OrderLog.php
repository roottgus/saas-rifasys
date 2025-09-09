<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLog extends Model
{
    protected $fillable = ['order_id','tenant_id','actor_id','action','notes','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }
}
