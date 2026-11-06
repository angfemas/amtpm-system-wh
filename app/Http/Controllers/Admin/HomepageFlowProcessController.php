<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageFlowProcessItem;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageFlowProcessController extends Controller
{
    protected ImageCompressionService $imageCompression;

    public function __construct(ImageCompressionService $imageCompression)
    {
        $this->imageCompression = $imageCompression;
    }

    public function index()
    {
        $items = HomepageFlowProcessItem::ordered()->get();
        return view('admin.homepage.flow-process.index', compact('items'));
    }

    public function create()
    {
        return view('admin.homepage.flow-process.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image_path')) {
            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/flow-process',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        HomepageFlowProcessItem::create($data);

        return redirect()
            ->route('admin.homepage.flow-process.index')
            ->with('success', 'Flow process item created successfully.');
    }

    public function edit(HomepageFlowProcessItem $flow)
    {
        return view('admin.homepage.flow-process.edit', compact('flow'));
    }

    public function update(Request $request, HomepageFlowProcessItem $flow)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? $flow->description,
            'order' => $validated['order'] ?? $flow->order,
            'is_active' => $validated['is_active'] ?? $flow->is_active,
        ];

        if ($request->hasFile('image_path')) {
            if ($flow->image_path) {
                Storage::disk('public')->delete($flow->image_path);
            }

            $imagePath = $this->imageCompression->compressAndStore(
                $request->file('image_path'),
                'homepage/flow-process',
                'public'
            );
            $data['image_path'] = $imagePath;
        }

        $flow->update($data);

        return redirect()
            ->route('admin.homepage.flow-process.index')
            ->with('success', 'Flow process item updated successfully.');
    }

    public function destroy(HomepageFlowProcessItem $flow)
    {
        if ($flow->image_path) {
            Storage::disk('public')->delete($flow->image_path);
        }

        $flow->delete();

        return redirect()
            ->route('admin.homepage.flow-process.index')
            ->with('success', 'Flow process item deleted successfully.');
    }
}
