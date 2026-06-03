<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistItemRequest;
use App\Http\Requests\UpdateChecklistItemRequest;
use App\Models\ChecklistItem;
use App\Models\ChecklistSubItem;
use App\Models\UnitCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        $checklistItems = ChecklistItem::with(['unitCategories', 'subItems'])
            ->active()
            ->orderBy('urutan')
            ->paginate(10);
        
        return view('checklist-items.index', compact('categories', 'checklistItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        return view('checklist-items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChecklistItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $categoryIds = $data['unit_category_ids'];
        $subItems = $data['sub_items'] ?? [];
        
        unset($data['unit_category_ids']);
        unset($data['sub_items']);
        
        $checklistItem = ChecklistItem::create($data);
        
        // Attach categories
        $checklistItem->unitCategories()->attach($categoryIds);
        
        // Create sub-items
        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                if (!empty($subItem['judul'])) {
                    ChecklistSubItem::create([
                        'checklist_item_id' => $checklistItem->id,
                        'judul' => $subItem['judul'],
                        'deskripsi' => $subItem['deskripsi'] ?? null,
                        'urutan' => $subItem['urutan'] ?? 0,
                    ]);
                }
            }
        }
        
        return redirect()
            ->route('checklist-items.index')
            ->with('success', 'Item checklist berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChecklistItem $checklistItem): View
    {
        $checklistItem->load(['unitCategories', 'subItems']);
        return view('checklist-items.show', compact('checklistItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChecklistItem $checklistItem): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        $checklistItem->load(['unitCategories', 'subItems']);
        return view('checklist-items.edit', compact('checklistItem', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistItemRequest $request, ChecklistItem $checklistItem): RedirectResponse
    {
        $data = $request->validated();
        $categoryIds = $data['unit_category_ids'];
        $subItems = $data['sub_items'] ?? [];
        
        unset($data['unit_category_ids']);
        unset($data['sub_items']);
        
        $checklistItem->update($data);
        
        // Sync categories
        $checklistItem->unitCategories()->sync($categoryIds);
        
        // Update/Create sub-items
        $existingIds = [];
        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                if (!empty($subItem['judul'])) {
                    if (!empty($subItem['id']) && isset($subItem['id'])) {
                        // Update existing
                        ChecklistSubItem::where('id', $subItem['id'])->update([
                            'judul' => $subItem['judul'],
                            'deskripsi' => $subItem['deskripsi'] ?? null,
                            'urutan' => $subItem['urutan'] ?? 0,
                        ]);
                        $existingIds[] = $subItem['id'];
                    } else {
                        // Create new
                        $newSubItem = ChecklistSubItem::create([
                            'checklist_item_id' => $checklistItem->id,
                            'judul' => $subItem['judul'],
                            'deskripsi' => $subItem['deskripsi'] ?? null,
                            'urutan' => $subItem['urutan'] ?? 0,
                        ]);
                        $existingIds[] = $newSubItem->id;
                    }
                }
            }
        }
        
        // Delete removed sub-items
        ChecklistSubItem::where('checklist_item_id', $checklistItem->id)
            ->whereNotIn('id', $existingIds)
            ->delete();
        
        return redirect()
            ->route('checklist-items.index')
            ->with('success', 'Item checklist berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistItem $checklistItem): RedirectResponse
    {
        $checklistItem->delete();
        
        return redirect()
            ->route('checklist-items.index')
            ->with('success', 'Item checklist berhasil dihapus.');
    }

    /**
     * Get checklist items by category
     */
    public function getByCategory(UnitCategory $category): View
    {
        $checklistItems = ChecklistItem::whereHas('unitCategories', function($q) use ($category) {
            $q->where('unit_categories.id', $category->id);
        })
            ->with(['unitCategories', 'subItems'])
            ->active()
            ->ordered()
            ->get();
        
        return view('checklist-items.by-category', compact('category', 'checklistItems'));
    }
}
