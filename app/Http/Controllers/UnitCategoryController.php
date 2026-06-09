<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitCategoryRequest;
use App\Http\Requests\UpdateUnitCategoryRequest;
use App\Models\UnitCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = UnitCategory::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }
        
        $categories = $query->withCount('units')
                           ->orderBy('name')
                           ->paginate(10)
                           ->withQueryString();
        
        return view('unit-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('unit-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitCategoryRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active');
            
            // Set default color if not provided
            if (empty($validated['color'])) {
                $validated['color'] = $this->generateRandomColor();
            }
            
            $category = UnitCategory::create($validated);
            
            return redirect()
                ->route('unit-categories.index')
                ->with('success', "Kategori unit '{$category->name}' berhasil ditambahkan.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori unit. Silakan coba lagi.');
        }
    }
    
    /**
     * Generate random color for category
     */
    private function generateRandomColor(): string
    {
        $colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1', '#e83e8c', '#17a2b8', '#6c757d'];
        return $colors[array_rand($colors)];
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitCategory $unitCategory): View
    {
        $units = $unitCategory->units()
                            ->with('warehouseArea')
                            ->orderBy('nomor_urut')
                            ->paginate(10);
                            
        return view('unit-categories.show', compact('unitCategory', 'units'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitCategory $unitCategory): View
    {
        return view('unit-categories.edit', compact('unitCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitCategoryRequest $request, UnitCategory $unitCategory): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active');
            $unitCategory->update($validated);
            
            return redirect()
                ->route('unit-categories.index')
                ->with('success', "Kategori unit '{$unitCategory->name}' berhasil diperbarui.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori unit. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UnitCategory $unitCategory)
    {
        try {
            // Check if category has units
            if ($unitCategory->units()->count() > 0) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menghapus kategori '{$unitCategory->name}' karena masih memiliki unit terkait."
                    ], 422);
                }

                return redirect()
                    ->route('unit-categories.index')
                    ->with('error', "Tidak dapat menghapus kategori '{$unitCategory->name}' karena masih memiliki unit terkait.");
            }
            
            $unitCategory->delete();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Kategori unit '{$unitCategory->name}' berhasil dihapus."
                ]);
            }

            return redirect()
                ->route('unit-categories.index')
                ->with('success', "Kategori unit '{$unitCategory->name}' berhasil dihapus.");
                
        } catch (\Exception $e) {
            return redirect()
                ->route('unit-categories.index')
                ->with('error', 'Gagal menghapus kategori unit. Silakan coba lagi.');
        }
    }
    
    /**
     * Toggle category status
     */
    public function toggleStatus(UnitCategory $unitCategory): RedirectResponse
    {
        try {
            $unitCategory->update([
                'is_active' => !$unitCategory->is_active
            ]);
            
            $status = $unitCategory->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()
                ->route('unit-categories.index')
                ->with('success', "Kategori unit '{$unitCategory->name}' berhasil {$status}.");
                
        } catch (\Exception $e) {
            return redirect()
                ->route('unit-categories.index')
                ->with('error', 'Gagal mengubah status kategori unit. Silakan coba lagi.');
        }
    }
}
