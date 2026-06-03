<?php

namespace App\Console\Commands;

use App\Services\MaintenanceScheduleService;
use Illuminate\Console\Command;

class SyncMaintenanceSchedulesCommand extends Command
{
    protected $signature = 'maintenance:sync-schedules';

    protected $description = 'Sinkronkan next_due_date per unit ke tabel maintenance_schedules (dasar: tanggal terakhir + interval hari).';

    public function handle(MaintenanceScheduleService $service): int
    {
        $n = $service->syncFromUnits();
        $this->info("Berhasil sinkron {$n} jadwal.");

        return self::SUCCESS;
    }
}
