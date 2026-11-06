<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Warehouse Sections
    |--------------------------------------------------------------------------
    |
    | This file contains the list of available sections/sections in the warehouse.
    | These sections can be assigned to users and managed by admin.
    |
    */

    'sections' => [
        'PKWH1' => 'PKWH1',
        'PKWH2' => 'PKWH2',
        'PKWH3' => 'PKWH3',
        'PKWH4' => 'PKWH4',
        'PKWH5' => 'PKWH5',
        'PKWH6' => 'PKWH6',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Section
    |--------------------------------------------------------------------------
    |
    | The default section to assign to new users if not specified.
    |
    */

    'default_section' => env('DEFAULT_SECTION', null),
];
