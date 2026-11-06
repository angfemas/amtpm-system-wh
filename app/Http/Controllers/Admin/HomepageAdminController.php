<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCarouselItem;
use App\Models\HomepageSliderRotateItem;
use App\Models\HomepageFlowProcessItem;
use App\Models\HomepageSection;

class HomepageAdminController extends Controller
{
    public function index()
    {
        return view('admin.homepage.index');
    }
}
