<?php

namespace App\Imports;

use App\Models\Unit;
use App\Models\UnitCategory;
use App\Models\WarehouseArea;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UnitsImport implements OnEachRow, WithHeadingRow, WithChunkReading
{
    use Importable;

    public int $imported = 0;
    public int $skipped = 0;

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $unitCode = $this->getValue($row, ['unit_code', 'kode_unit']);
        $unitName = $this->getValue($row, ['nama_unit', 'unit_name', 'name']);
        $categoryValue = $this->getValue($row, ['unit_category', 'category']);
        $areaValue = $this->getValue($row, ['warehouse_area', 'area']);

        if ($unitCode === '' || $unitName === '' || $categoryValue === '' || $areaValue === '') {
            $this->skipped++;
            return;
        }

        $categoryId = $this->resolveCategoryId($categoryValue);
        $areaId = $this->resolveAreaId($areaValue);

        if ($categoryId === null || $areaId === null) {
            $this->skipped++;
            return;
        }

        $jenisMaintenance = strtolower($this->getValue($row, ['jenis_maintenance', 'maintenance_type', 'type']));
        if (!in_array($jenisMaintenance, ['preventive', 'corrective', 'predictive'], true)) {
            $jenisMaintenance = 'preventive';
        }

        $maintenanceDate = $row['tanggal_maintenance_terakhir'] ?? $row['maintenance_date'] ?? null;
        if ($maintenanceDate instanceof \DateTime) {
            $maintenanceDate = Carbon::instance($maintenanceDate)->toDateString();
        }

        $tanggalMaintenanceTerakhir = $maintenanceDate ? Carbon::parse($maintenanceDate)->toDateString() : null;

        $intervalHari = (int) $this->getValue($row, ['interval_hari', 'interval', 'maintenance_interval']);
        $intervalHari = $intervalHari > 0 ? $intervalHari : 30;

        $kilometer = (float) $this->getValue($row, ['kilometer', 'km']);
        $hourMeter = (float) $this->getValue($row, ['hour_meter', 'hours', 'hour_meter']);

        $isActive = $this->parseBoolean($this->getValue($row, ['is_active', 'active']));

        $status = strtolower($this->getValue($row, ['status']));
        $validStatuses = ['active', 'maintenance', 'overdue', 'inactive'];
        if (!in_array($status, $validStatuses, true)) {
            $status = $isActive ? 'active' : 'inactive';
        }

        $keterangan = $this->getValue($row, ['keterangan', 'description', 'notes']);

        $nomorValue = $this->getValue($row, ['nomor_urut', 'nomor', 'no', 'no_urut']);
        $nomorUrut = ($nomorValue !== '' && is_numeric($nomorValue) && (int) $nomorValue > 0)
            ? (int) $nomorValue
            : null;

        Unit::updateOrCreate([
            'kode_unit' => $unitCode,
        ], [
            'nomor_urut' => $nomorUrut,
            'nama_unit' => $unitName,
            'unit_category_id' => $categoryId,
            'warehouse_area_id' => $areaId,
            'jenis_maintenance' => $jenisMaintenance,
            'tanggal_maintenance_terakhir' => $tanggalMaintenanceTerakhir,
            'interval_hari' => $intervalHari,
            'kilometer' => $kilometer,
            'hour_meter' => $hourMeter,
            'status' => $status,
            'is_active' => $isActive,
            'keterangan' => $keterangan,
        ]);

        $this->imported++;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function getValue(array $row, array $keys): string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return trim((string) $row[$key]);
            }
        }

        return '';
    }

    private function resolveCategoryId(string $value): ?int
    {
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return UnitCategory::find((int) $value)?->id;
        }

        $category = UnitCategory::firstOrCreate([
            'name' => $value,
        ], [
            'is_active' => true,
        ]);

        return $category->id;
    }

    private function resolveAreaId(string $value): ?int
    {
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return WarehouseArea::find((int) $value)?->id;
        }

        $area = WarehouseArea::firstOrCreate([
            'name' => $value,
        ], [
            'is_active' => true,
        ]);

        return $area->id;
    }

    private function parseBoolean(string $value): bool
    {
        $value = strtolower(trim($value));

        return in_array($value, ['1', 'true', 'yes', 'y', 'active'], true);
    }
}
