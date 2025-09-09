<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    */
    'default' => env('MAIL_MAILER', 'smtp'), // usa "smtp" por defecto

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Tip: para Hostinger normalmente:
    | MAIL_HOST=smtp.hostinger.com
    | MAIL_PORT=587
    | MAIL_ENCRYPTION=tls
    | MAIL_USERNAME=tu@dominio.com
    | MAIL_PASSWORD=********
    |
    */

    'mailers' => [

        'smtp' => [
            'transport'    => 'smtp',
            'scheme'       => env('MAIL_SCHEME'),              // opcional
            'url'          => env('MAIL_URL'),                 // opcional
            'host'         => env('MAIL_HOST', '127.0.0.1'),
            'port'         => env('MAIL_PORT', 1025),
            'encryption'   => env('MAIL_ENCRYPTION', 'tls'),   // <— importante para proveedores reales
            'username'     => env('MAIL_USERNAME'),
            'password'     => env('MAIL_PASSWORD'),
            'timeout'      => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),

            // (Opcional) Úsalo solo si tu hosting tiene certificados raros.
            // Controlado por env: MAIL_VERIFY_PEER=true/false
            'stream' => [
                'ssl' => [
                    'verify_peer'       => env('MAIL_VERIFY_PEER', true),
                    'verify_peer_name'  => env('MAIL_VERIFY_PEER', true),
                    'allow_self_signed' => ! env('MAIL_VERIFY_PEER', true),
                ],
            ],
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path'      => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        // Si quieres que al fallar SMTP se registre en log en lugar de romper el job,
        // cambia MAIL_MAILER=failover en tu .env
        'failover' => [
            'transport'  => 'failover',
            'mailers'    => ['smtp', 'log'],
            'retry_after'=> 60,
        ],

        'roundrobin' => [
            'transport'  => 'roundrobin',
            'mailers'    => ['ses', 'postmark'],
            'retry_after'=> 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name'    => env('MAIL_FROM_NAME', 'Example'),
    ],

    // (Opcional) Global reply-to
    'reply_to' => [
        'address' => env('MAIL_REPLY_TO_ADDRESS'),
        'name'    => env('MAIL_REPLY_TO_NAME'),
    ],

    // Dirección interna para notificar al admin (usada en CheckoutController)
    'admin_address' => env('MAIL_ADMIN_ADDRESS', 'admin@tusitio.com'),
];
