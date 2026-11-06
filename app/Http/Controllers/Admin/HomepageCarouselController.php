<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCarouselItem;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageCarouselController extends Controller
{
    protected ImageCompressionService $imageCompression;

    public function __construct(ImageCompressionService $imageCompression)
    {
        $this->imageCompression = $imageCompression;
    }

    public function index()
    {
        $items = HomepageCarouselItem::ordered()->get();
        return view('admin.homepage.carousel.index', compact('items'));
    }

    public function create()
    {
        return view('admin.homepage.carousel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'order' => 'nullable|integer',
            'caption_position' => 'in:top,bottom,left,right',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'caption_position' => $validated['caption_position'] ?? 'bottom',
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image_path')) {
            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/carousel',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        HomepageCarouselItem::create($data);

        return redirect()
            ->route('admin.homepage.carousel.index')
            ->with('success', 'Carousel item created successfully.');
    }

    public function edit(HomepageCarouselItem $carousel)
    {
        return view('admin.homepage.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, HomepageCarouselItem $carousel)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'order' => 'integer',
            'caption_position' => 'in:top,bottom,left,right',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'] ?? $carousel->title,
            'description' => $validated['description'] ?? $carousel->description,
            'order' => $validated['order'] ?? $carousel->order,
            'caption_position' => $validated['caption_position'] ?? $carousel->caption_position,
            'is_active' => $validated['is_active'] ?? $carousel->is_active,
        ];

        if ($request->hasFile('image_path')) {
            if ($carousel->image_path) {
                Storage::disk('public')->delete($carousel->image_path);
            }

            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/carousel',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        $carousel->update($data);

        return redirect()
            ->route('admin.homepage.carousel.index')
            ->with('success', 'Carousel item updated successfully.');
    }

    public function destroy(HomepageCarouselItem $carousel)
    {
        if ($carousel->image_path) {
            Storage::disk('public')->delete($carousel->image_path);
        }

        $carousel->delete();

        return redirect()
            ->route('admin.homepage.carousel.index')
            ->with('success', 'Carousel item deleted successfully.');
    }
}
