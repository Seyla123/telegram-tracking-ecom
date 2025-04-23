<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Response Messages
    |--------------------------------------------------------------------------
    |
    | Here you may specify default messages for various response types.
    */
    
    'messages' => [
        'success' => 'Operation completed successfully',
        'error' => 'An error occurred',
        'token' => 'Token generated successfully'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Token Response Settings
    |--------------------------------------------------------------------------
    */
    
    'token' => [
        'refresh_cookie' => [
            'name' => 'refresh_token',
            'minutes' => 43200, // 30 days
            'secure' => true,
            'http_only' => true
        ]
    ]
];