<?php

return [
    'appkey' => env('PHPBB_BRIDGE_API_KEY', 'yoursecretapikey'),
    'client_auth' => false,
    'user_model' => [
        'username_column' => 'username',
        'password_column' => 'password',
    ],
];
