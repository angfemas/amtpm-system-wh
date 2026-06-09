<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\UnitCategory;
use App\Models\WarehouseArea;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample unit categories
        $categories = [
            ['name' => 'Heavy Equipment', 'description' => 'Heavy machinery and equipment'],
            ['name' => 'Light Vehicle', 'description' => 'Light vehicles and trucks'],
            ['name' => 'Tools', 'description' => 'Hand tools and equipment'],
        ];

        foreach ($categories as $category) {
            UnitCategory::firstOrCreate(['name' => $category['name']], $category);
        }

        // Create sample warehouse areas
        $areas = [
            ['name' => 'Warehouse A', 'description' => 'Main warehouse for heavy equipment'],
            ['name' => 'Warehouse B', 'description' => 'Secondary warehouse for light vehicles'],
            ['name' => 'Outdoor Storage', 'description' => 'Open storage area for large equipment'],
        ];

        foreach ($areas as $area) {
            WarehouseArea::firstOrCreate(['name' => $area['name']], $area);
        }

        // Create sample units
        $units = [
            [
                'nomor_urut' => 1,
                'nama_unit' => 'Excavator CAT 320',
                'kode_unit' => 'EXC-001',
                'unit_category_id' => 1,
                'warehouse_area_id' => 1,
                'status' => 'active',
                'kilometer' => 1500.50,
                'hour_meter' => 2500.75,
                'keterangan' => 'Heavy excavator for digging operations',
            ],
            [
                'nomor_urut' => 2,
                'nama_unit' => 'Dump Truck Hino 500',
                'kode_unit' => 'DT-001',
                'unit_category_id' => 2,
                'warehouse_area_id' => 1,
                'status' => 'active',
                'kilometer' => 25000.00,
                'hour_meter' => 3200.00,
                'keterangan' => 'Heavy dump truck for material transport',
            ],
            [
                'nomor_urut' => 3,
                'nama_unit' => 'Forklift Toyota',
                'kode_unit' => 'FL-001',
                'unit_category_id' => 3,
                'warehouse_area_id' => 2,
                'status' => 'active',
                'kilometer' => 5000.00,
                'hour_meter' => 1800.00,
                'keterangan' => 'Electric forklift for warehouse operations',
            ],
            [
                'nomor_urut' => 4,
                'nama_unit' => 'Bulldozer Komatsu D155',
                'kode_unit' => 'BD-001',
                'unit_category_id' => 1,
                'warehouse_area_id' => 3,
                'status' => 'active',
                'kilometer' => 8000.00,
                'hour_meter' => 4500.00,
                'keterangan' => 'Heavy bulldozer for earth moving',
            ],
            [
                'nomor_urut' => 5,
                'nama_unit' => 'Crane Tadano',
                'kode_unit' => 'CR-001',
                'unit_category_id' => 1,
                'warehouse_area_id' => 1,
                'status' => 'active',
                'kilometer' => 800.00,
                'hour_meter' => 1200.00,
                'keterangan' => 'Mobile crane for lifting operations',
            ],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['kode_unit' => $unit['kode_unit']], $unit);
        }
    }
}
