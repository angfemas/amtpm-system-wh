<?php

namespace App\Http\Controllers;

use App\Models\HomepageCarouselItem;
use App\Models\HomepageFlowProcessItem;
use App\Models\HomepageSection;
use App\Models\HomepageSliderRotateItem;
use App\Models\RedWhiteTag;
use Illuminate\Support\Facades\Schema;

class HomepageController extends Controller
{
    public function index()
    {
        $carouselItems = HomepageCarouselItem::active()->ordered()->get();
        $sliderRotateItems = HomepageSliderRotateItem::active()->ordered()->get();
        $sliderRotateItems->each(function (HomepageSliderRotateItem $item) {
            if ($item->subtitle === 'PENGENCERAN') {
                $item->subtitle = 'PENGENCANGAN';
            }
        });
        $flowProcessItems = HomepageFlowProcessItem::active()->ordered()->get();
        $amtpmBackground = HomepageSection::byKey('amtpm_background')->active()->first();
        $maintenanceDataSection = HomepageSection::byKey('maintenance_data')->active()->first();

        $redTags = collect();
        $whiteTags = collect();
        $openRedCount = 0;
        $openWhiteCount = 0;

        if (Schema::hasTable('red_white_tags')) {
            $redTags = RedWhiteTag::with('unit')
                ->where('tag_type', 'red_tag')
                ->where('status', 'open')
                ->latest()
                ->take(6)
                ->get();

            $whiteTags = RedWhiteTag::with('unit')
                ->where('tag_type', 'white_tag')
                ->where('status', 'open')
                ->latest()
                ->take(6)
                ->get();

            $openRedCount = RedWhiteTag::where('tag_type', 'red_tag')->where('status', 'open')->count();
            $openWhiteCount = RedWhiteTag::where('tag_type', 'white_tag')->where('status', 'open')->count();
        }

        return view('welcome', compact(
            'carouselItems',
            'sliderRotateItems',
            'flowProcessItems',
            'amtpmBackground',
            'maintenanceDataSection',
            'redTags',
            'whiteTags',
            'openRedCount',
            'openWhiteCount'
        ));
    }
}
