<?php

// Script untuk memperbaiki max_allowed_packet MySQL
// Jalankan script ini untuk meningkatkan max_allowed_packet menjadi 64MB

echo "Memperbaiki max_allowed_packet MySQL...\n";

// 1. Cek ukuran packet saat ini
$command = '"C:\\xampp\\mysql\\bin\\mysql.exe" -u root -p -e "SHOW VARIABLES LIKE \"max_allowed_packet\";"';
$output = shell_exec($command);
echo "Current max_allowed_packet: " . $output . "\n";

// 2. Set ke 64MB (67108864 bytes)
$command = '"C:\\xampp\\mysql\\bin\\mysql.exe" -u root -p -e "SET GLOBAL max_allowed_packet = 67108864;"';
shell_exec($command);
echo "Set max_allowed_packet to 64MB\n";

// 3. Restart MySQL service
echo "Restarting MySQL service...\n";
shell_exec('net stop mysql');
sleep(3);
shell_exec('net start mysql');

echo "Done! max_allowed_packet sudah diperbaiki.\n";
echo "Silakan coba submit maintenance log lagi.\n";
?>
