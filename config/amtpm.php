<?php

return [

    'display_name' => env('APP_DISPLAY_NAME', env('APP_NAME', 'Warehouse AMTPM System')),

    'reminders' => [
        'daily_at' => env('MAINTENANCE_REMINDER_TIME', '06:30'),
    ],

];
