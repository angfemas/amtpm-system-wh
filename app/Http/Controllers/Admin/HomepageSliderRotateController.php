<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSliderRotateItem;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageSliderRotateController extends Controller
{
    protected ImageCompressionService $imageCompression;

    public function __construct(ImageCompressionService $imageCompression)
    {
        $this->imageCompression = $imageCompression;
    }

    public function index()
    {
        $items = HomepageSliderRotateItem::ordered()->get();
        return view('admin.homepage.slider-rotate.index', compact('items'));
    }

    public function create()
    {
        return view('admin.homepage.slider-rotate.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'position' => 'integer|min:1|max:4',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'subtitle' => $validated['subtitle'] ?? null,
            'position' => $validated['position'] ?? 1,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('icon_path')) {
            $iconPath = $this->imageCompression->compressAndStore(
                $request->file('icon_path'),
                'homepage/slider-rotate',
                'public'
            );
            $data['icon_path'] = $iconPath;
        }

        HomepageSliderRotateItem::create($data);

        return redirect()
            ->route('admin.homepage.slider-rotate.index')
            ->with('success', 'Slider rotate item created successfully.');
    }

    public function edit(HomepageSliderRotateItem $slider)
    {
        return view('admin.homepage.slider-rotate.edit', compact('slider'));
    }

    public function update(Request $request, HomepageSliderRotateItem $slider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'icon_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'position' => 'integer|min:1|max:4',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'subtitle' => $validated['subtitle'] ?? $slider->subtitle,
            'position' => $validated['position'] ?? $slider->position,
            'is_active' => $validated['is_active'] ?? $slider->is_active,
        ];

        if ($request->hasFile('icon_path')) {
            if ($slider->icon_path) {
                Storage::disk('public')->delete($slider->icon_path);
            }

            $iconPath = $this->imageCompression->compressAndStore(
                $request->file('icon_path'),
                'homepage/slider-rotate',
                'public'
            );
            $data['icon_path'] = $iconPath;
        }

        $slider->update($data);

        return redirect()
            ->route('admin.homepage.slider-rotate.index')
            ->with('success', 'Slider rotate item updated successfully.');
    }

    public function destroy(HomepageSliderRotateItem $slider)
    {
        if ($slider->icon_path) {
            Storage::disk('public')->delete($slider->icon_path);
        }

        $slider->delete();

        return redirect()
            ->route('admin.homepage.slider-rotate.index')
            ->with('success', 'Slider rotate item deleted successfully.');
    }
}
