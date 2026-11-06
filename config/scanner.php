<?php

return [
    // Scanner configuration
    'scan_width' => env('SCANNER_SCAN_WIDTH', 320),
    'scan_height' => env('SCANNER_SCAN_HEIGHT', 240),
    'scan_interval' => env('SCANNER_SCAN_INTERVAL', 120), // milliseconds

    // Camera configuration
    'camera_width' => env('SCANNER_CAMERA_WIDTH', 640),
    'camera_height' => env('SCANNER_CAMERA_HEIGHT', 480),
    'facing_mode' => env('SCANNER_FACING_MODE', 'environment'),

    // Worker configuration
    'worker_script' => env('SCANNER_WORKER_SCRIPT', '/js/qr-scanner-worker.js'),
];
