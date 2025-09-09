<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrderStatus;

class Order extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id','rifa_id','code','customer_name','customer_phone','customer_email',
        'payment_account_id','status','total_amount','voucher_path','expires_at','notes',
    ];

    protected $casts = [
        'status'       => OrderStatus::class,
        'total_amount' => 'decimal:2',
        'expires_at'   => 'datetime',
    ];

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function rifa(): BelongsTo { return $this->belongsTo(Rifa::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function paymentAccount(): BelongsTo { return $this->belongsTo(PaymentAccount::class); }

    /** Bitácora (para el RelationManager Logs) */
    public function logs(): HasMany
    {
        return $this->hasMany(\App\Models\OrderLog::class)->latest();
    }
}
