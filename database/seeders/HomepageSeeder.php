<?php

namespace Database\Seeders;

use App\Models\HomepageCarouselItem;
use App\Models\HomepageSliderRotateItem;
use App\Models\HomepageFlowProcessItem;
use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default carousel items
        $carouselItems = [
            [
                'title' => 'AMTPM System',
                'description' => 'Autonomous Maintenance Total Productive Management untuk efisiensi maksimal',
                'image_path' => '/resources/image/web/carousel1.jpg',
                'order' => 1,
                'is_active' => true,
                'caption_position' => 'bottom',
            ],
            [
                'title' => 'Manajemen Maintenance',
                'description' => 'Kelola maintenance dengan sistem red tag dan white tag',
                'image_path' => '/resources/image/web/carousel2.jpg',
                'order' => 2,
                'is_active' => true,
                'caption_position' => 'bottom',
            ],
            [
                'title' => 'Efisiensi Operasional',
                'description' => 'Tingkatkan produktivitas dengan sistem terintegrasi',
                'image_path' => '/resources/image/web/carousel3.jpg',
                'order' => 3,
                'is_active' => true,
                'caption_position' => 'bottom',
            ],
        ];

        foreach ($carouselItems as $item) {
            HomepageCarouselItem::create($item);
        }

        // Create slider rotate items (4 pillars)
        $sliderRotateItems = [
            [
                'title' => 'CLEANING',
                'subtitle' => 'PEMBERSIHAN',
                'description' => 'Membersihkan alat kerja kita material handling sehingga terhindar dari kotoran debu scrub dan lainnya',
                'icon_path' => '/resources/image/web/icon-cleaning.png',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'TIGHTENING',
                'subtitle' => 'PENGENCANGAN',
                'description' => 'Mengencangkan semua baut dan mur untuk mencegah kebocoran dan getaran',
                'icon_path' => '/resources/image/web/icon-tightening.png',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'LUBRICATION',
                'subtitle' => 'PELUMASAN',
                'description' => 'Melumasi bagian yang bergerak untuk mencegah aus dan kerusakan',
                'icon_path' => '/resources/image/web/icon-lubrication.png',
                'position' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'INSPECTION',
                'subtitle' => 'INSPEKSI',
                'description' => 'Memeriksa kondisi peralatan secara rutin untuk mendeteksi masalah dini',
                'icon_path' => '/resources/image/web/icon-inspection.png',
                'position' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($sliderRotateItems as $item) {
            HomepageSliderRotateItem::updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }

        // Create flow process items
        $flowProcessItems = [
            [
                'title' => 'Perencanaan',
                'description' => 'Rencanakan jadwal maintenance berdasarkan prioritas',
                'image_path' => '/resources/image/web/flow1.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Pelaksanaan',
                'description' => 'Lakukan maintenance sesuai standar yang ditetapkan',
                'image_path' => '/resources/image/web/flow2.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Pemantauan',
                'description' => 'Pantau hasil dan kinerja peralatan',
                'image_path' => '/resources/image/web/flow3.jpg',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Evaluasi',
                'description' => 'Evaluasi dan tingkatkan proses maintenance',
                'image_path' => '/resources/image/web/flow4.jpg',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($flowProcessItems as $item) {
            HomepageFlowProcessItem::create($item);
        }

        // Create sections
        $sections = [
            [
                'key' => 'amtpm_background',
                'title' => 'Apa itu AMTPM?',
                'content' => '<p>Autonomous Maintenance Total Productive Maintenance adalah pendekatan sistematis untuk pemeliharaan peralatan yang melibatkan operator dalam kegiatan pemeliharaan sehari-hari. Tujuannya adalah untuk meningkatkan efisiensi operasional dan mengurangi downtime peralatan.</p>',
                'image_path' => '/resources/image/web/amtpm-bg.jpg',
                'background_color' => '#f5f5f5',
                'text_color' => '#333333',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'maintenance_data',
                'title' => 'Data Maintenance',
                'content' => '<p>Pantau data maintenance secara real-time dengan sistem red tag dan white tag untuk identifikasi masalah dan tracking perbaikan.</p>',
                'image_path' => null,
                'background_color' => '#ffffff',
                'text_color' => '#333333',
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::create($section);
        }
    }
}
