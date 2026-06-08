<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitImportTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                10,
                'UNIT001',
                'Unit Contoh',
                'Electrical',
                'Gudang Utama',
                'preventive',
                '2026-05-01',
                30,
                1000,
                150,
                'active',
                '1',
                'Keterangan contoh unit',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nomor_urut',
            'kode_unit',
            'nama_unit',
            'unit_category',
            'warehouse_area',
            'jenis_maintenance',
            'tanggal_maintenance_terakhir',
            'interval_hari',
            'kilometer',
            'hour_meter',
            'status',
            'is_active',
            'keterangan',
        ];
    }
}
