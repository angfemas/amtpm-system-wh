<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistItem;

class ChecklistItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checklistItems = [
            [
                'nama_item' => 'Engine Oil Check',
                'unit_category_id' => 1,
                'deskripsi' => 'Check engine oil level and condition',
                'tipe' => 'checkbox',
                'urutan' => 1,
            ],
            [
                'nama_item' => 'Hydraulic System Check',
                'unit_category_id' => 1,
                'deskripsi' => 'Inspect hydraulic hoses and connections',
                'tipe' => 'checkbox',
                'urutan' => 2,
            ],
            [
                'nama_item' => 'Brake System Check',
                'unit_category_id' => 2,
                'deskripsi' => 'Test brake functionality and fluid level',
                'tipe' => 'checkbox',
                'urutan' => 1,
            ],
            [
                'nama_item' => 'Tire Pressure Check',
                'unit_category_id' => 2,
                'deskripsi' => 'Check tire pressure and condition',
                'tipe' => 'checkbox',
                'urutan' => 2,
            ],
            [
                'nama_item' => 'Battery Check',
                'unit_category_id' => 3,
                'deskripsi' => 'Inspect battery terminals and charge level',
                'tipe' => 'checkbox',
                'urutan' => 1,
            ],
            [
                'nama_item' => 'Filter Replacement',
                'unit_category_id' => 1,
                'deskripsi' => 'Replace air, oil, and fuel filters',
                'tipe' => 'checkbox',
                'urutan' => 3,
            ],
            [
                'nama_item' => 'Greasing Points',
                'unit_category_id' => 1,
                'deskripsi' => 'Apply grease to all lubrication points',
                'tipe' => 'checkbox',
                'urutan' => 4,
            ],
            [
                'nama_item' => 'Safety Equipment Check',
                'unit_category_id' => 2,
                'deskripsi' => 'Check fire extinguisher, first aid kit, and safety signs',
                'tipe' => 'checkbox',
                'urutan' => 3,
            ],
        ];

        foreach ($checklistItems as $item) {
            ChecklistItem::firstOrCreate(['nama_item' => $item['nama_item']], $item);
        }
    }
}
