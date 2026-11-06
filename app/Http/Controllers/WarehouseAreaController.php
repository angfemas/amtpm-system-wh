<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseAreaRequest;
use App\Http\Requests\UpdateWarehouseAreaRequest;
use App\Models\WarehouseArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = WarehouseArea::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filter by capacity range
        if ($request->has('min_capacity') && $request->min_capacity) {
            $query->where('capacity', '>=', $request->min_capacity);
        }
        
        if ($request->has('max_capacity') && $request->max_capacity) {
            $query->where('capacity', '<=', $request->max_capacity);
        }
        
        $areas = $query->withCount('units')
                       ->orderBy('name')
                       ->paginate(10)
                       ->withQueryString();
        
        return view('warehouse-areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('warehouse-areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseAreaRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            // Set default color if not provided
            if (empty($validated['color'])) {
                $validated['color'] = $this->generateRandomColor();
            }
            
            $area = WarehouseArea::create($validated);

            return redirect()
                ->route('warehouse-areas.index')
                ->with('success', trans('warehouse.area.created', ['name' => $area->name]));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', trans('warehouse.area.create_failed'));
        }
    }
    
    /**
     * Generate random color for area
     */
    private function generateRandomColor(): string
    {
        $colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1', '#e83e8c', '#17a2b8', '#6c757d'];
        return $colors[array_rand($colors)];
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseArea $warehouseArea): View
    {
        $units = $warehouseArea->units()
                             ->with('unitCategory')
                             ->orderBy('nama_unit')
                             ->paginate(10);
                             
        return view('warehouse-areas.show', compact('warehouseArea', 'units'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseArea $warehouseArea): View
    {
        return view('warehouse-areas.edit', compact('warehouseArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseAreaRequest $request, WarehouseArea $warehouseArea): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $warehouseArea->update($validated);

            return redirect()
                ->route('warehouse-areas.index')
                ->with('success', trans('warehouse.area.updated', ['name' => $warehouseArea->name]));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', trans('warehouse.area.update_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseArea $warehouseArea)
    {
        try {
            // Check if area has units
            if ($warehouseArea->units()->count() > 0) {
                $message = trans('warehouse.area.has_units', ['name' => $warehouseArea->name]);
                if (request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()
                    ->route('warehouse-areas.index')
                    ->with('error', $message);
            }

            $warehouseArea->delete();
            $message = trans('warehouse.area.deleted', ['name' => $warehouseArea->name]);
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message, 'data' => $warehouseArea], 200);
            }
            return redirect()
                ->route('warehouse-areas.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            $message = trans('warehouse.area.delete_failed');
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()
                ->route('warehouse-areas.index')
                ->with('error', $message);
        }
    }
    
    /**
     * Toggle area status
     */
    public function toggleStatus(WarehouseArea $warehouseArea): RedirectResponse
    {
        try {
            $warehouseArea->update([
                'is_active' => !$warehouseArea->is_active
            ]);

            $status = $warehouseArea->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->route('warehouse-areas.index')
                ->with('success', trans('warehouse.area.status_changed', ['name' => $warehouseArea->name, 'status' => $status]));

        } catch (\Exception $e) {
            return redirect()
                ->route('warehouse-areas.index')
                ->with('error', trans('warehouse.area.status_change_failed'));
        }
    }
}
