<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AMTPM System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration specific to AMTPM Warehouse System
    |
    */

    'default_role' => env('AMTPM_DEFAULT_ROLE', 'operator'),

    'allowed_roles' => [
        'admin',
        'leader',
        'operator',
    ],
];
