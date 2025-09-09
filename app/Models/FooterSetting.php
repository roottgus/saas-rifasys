<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = [
        'tenant_id', 'brand_name', 'logo_path', 'description',
        'email', 'phone', 'website_url', 'address',
        'socials', 'quick_links', 'terms_url', 'privacy_url',
        'custom_html', 'bg_color', 'text_color',
    ];

    protected $casts = [
        'socials'     => 'array',
        'quick_links' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

 