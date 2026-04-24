<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    | Server key dari dashboard Midtrans. Gunakan sandbox key untuk development.
    | Jangan pernah expose key ini ke client-side.
    */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    | Client key digunakan di frontend (Snap.js), bukan di backend.
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Production Mode
    |--------------------------------------------------------------------------
    | Set ke true ketika sudah siap production.
    | Set ke false untuk menggunakan sandbox environment Midtrans.
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Snap URL
    |--------------------------------------------------------------------------
    | URL untuk embed Snap.js di frontend Anda.
    */
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];
