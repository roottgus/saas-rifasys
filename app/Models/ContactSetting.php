<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'whatsapp_phone','whatsapp_message','show_whatsapp_widget',
        'email','website_url','address',
        'instagram_url','facebook_url','tiktok_url','youtube_url','telegram_url',
    ];

    protected $casts = [
        'show_whatsapp_widget' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function getWhatsappPhoneAttribute()
{
    return $this->attributes['whatsapp_phone'] ?? $this->attributes['whatsapp_number'] ?? null;
}

}
