<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageSectionController extends Controller
{
    protected ImageCompressionService $imageCompression;

    public function __construct(ImageCompressionService $imageCompression)
    {
        $this->imageCompression = $imageCompression;
    }

    public function index()
    {
        $sections = HomepageSection::ordered()->get();
        return view('admin.homepage.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.homepage.sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:homepage_sections,key',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = [
            'key' => $validated['key'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'background_color' => $validated['background_color'] ?? null,
            'text_color' => $validated['text_color'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image_path')) {
            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/sections',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        HomepageSection::create($data);

        return redirect()
            ->route('admin.homepage.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(HomepageSection $section)
    {
        return view('admin.homepage.sections.edit', compact('section'));
    }

    public function update(Request $request, HomepageSection $section)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:homepage_sections,key,' . $section->id,
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = [
            'key' => $validated['key'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? $section->content,
            'background_color' => $validated['background_color'] ?? $section->background_color,
            'text_color' => $validated['text_color'] ?? $section->text_color,
            'order' => $validated['order'] ?? $section->order,
            'is_active' => $validated['is_active'] ?? $section->is_active,
        ];

        if ($request->hasFile('image_path')) {
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }

            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/sections',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        $section->update($data);

        return redirect()
            ->route('admin.homepage.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(HomepageSection $section)
    {
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();

        return redirect()
            ->route('admin.homepage.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}
