<?php

namespace App\Services;

use App\Models\MaintenanceSchedule;
use App\Models\Unit;

class MaintenanceScheduleService
{
    /**
     * Upsert satu baris jadwal per unit aktif berdasarkan tanggal terakhir + interval hari.
     */
    public function syncFromUnits(): int
    {
        $count = 0;

        Unit::query()
            ->where('is_active', true)
            ->whereNotNull('tanggal_maintenance_terakhir')
            ->each(function (Unit $unit) use (&$count) {
                $due = $unit->maintenance_due_date;
                if ($due === null) {
                    return;
                }

                MaintenanceSchedule::query()->updateOrCreate(
                    ['unit_id' => $unit->id],
                    ['next_due_date' => $due->toDateString()],
                );

                $count++;
            });

        return $count;
    }
}
