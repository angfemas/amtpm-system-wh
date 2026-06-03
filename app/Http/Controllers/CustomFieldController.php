<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomFieldRequest;
use App\Http\Requests\UpdateCustomFieldRequest;
use App\Models\CustomField;
use App\Models\UnitCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        $customFields = CustomField::with('unitCategory')
            ->active()
            ->orderBy('unit_category_id')
            ->orderBy('urutan')
            ->paginate(10);
        
        return view('custom-fields.index', compact('categories', 'customFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        return view('custom-fields.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomFieldRequest $request): RedirectResponse
    {
        CustomField::create($request->validated());
        
        return redirect()
            ->route('custom-fields.index')
            ->with('success', 'Custom field berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomField $customField): View
    {
        return view('custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        return view('custom-fields.edit', compact('customField', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomFieldRequest $request, CustomField $customField): RedirectResponse
    {
        $customField->update($request->validated());
        
        return redirect()
            ->route('custom-fields.index')
            ->with('success', 'Custom field berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomField $customField): RedirectResponse
    {
        $customField->delete();
        
        return redirect()
            ->route('custom-fields.index')
            ->with('success', 'Custom field berhasil dihapus.');
    }

    /**
     * Get custom fields by category
     */
    public function getByCategory(UnitCategory $category): View
    {
        $customFields = CustomField::where('unit_category_id', $category->id)
            ->active()
            ->ordered()
            ->get();
        
        return view('custom-fields.by-category', compact('category', 'customFields'));
    }
}
