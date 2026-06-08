<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Exports\UnitImportTemplateExport;
use App\Imports\UnitsImport;
use App\Models\Unit;
use App\Models\UnitCategory;
use App\Models\WarehouseArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Unit::with(['unitCategory', 'warehouseArea']);

        // Search functionality
        if ($search = $request->get('search')) {
            $query->search($search);
        }

        // Filter functionality
        if ($status = $request->get('status')) {
            $query->byStatus($status);
        }

        if ($categoryId = $request->get('category_id')) {
            $query->byCategory($categoryId);
        }

        if ($areaId = $request->get('area_id')) {
            $query->byArea($areaId);
        }

        $units = $query->orderByRaw('nomor_urut IS NULL, nomor_urut ASC')
            ->orderBy('nama_unit')
            ->paginate(10)
            ->withQueryString();

        // Get filter options for dropdowns
        $categories = UnitCategory::active()->orderBy('name')->pluck('name', 'id');
        $areas = WarehouseArea::active()->orderBy('name')->pluck('name', 'id');

        return view('units.index', compact('units', 'categories', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        $areas = WarehouseArea::active()->orderBy('name')->get();
        $nextNomorUrut = Unit::nextNomorUrut();
        
        return view('units.create', compact('categories', 'areas', 'nextNomorUrut'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Auto-assign nomor urut (last number + 1) when left blank on manual add.
            if (empty($data['nomor_urut'])) {
                $data['nomor_urut'] = Unit::nextNomorUrut();
            }

            if ($request->hasFile('foto_unit')) {
                $data['foto_unit'] = $request->file('foto_unit')->store('units/photos', 'public');
            }
            
            // Generate unique QR code
            $data['qr_code'] = 'UNIT-' . strtoupper(uniqid());
            
            // Ensure unit is active by default
            $data['is_active'] = true;
            
            $unit = Unit::create($data);
            
            return redirect()
                ->route('units.index')
                ->with('success', "Unit '{$unit->nama_unit}' berhasil ditambahkan.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }

    /**
     * Import units from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        if (!class_exists(\ZipArchive::class)) {
            $errorMessage = 'PHP extension ZipArchive tidak ditemukan. Silakan aktifkan ekstensi php_zip pada konfigurasi PHP agar file XLSX dapat diimpor.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 422);
            }

            return redirect()
                ->route('units.index')
                ->with('error', $errorMessage);
        }

        $import = new UnitsImport();

        try {
            Excel::import($import, $request->file('file'));

            $message = "Import selesai. {$import->imported} baris berhasil diproses.";
            if ($import->skipped > 0) {
                $message .= " {$import->skipped} baris dilewati karena data tidak lengkap atau kategori/area tidak valid.";
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'imported' => $import->imported,
                    'skipped' => $import->skipped,
                ]);
            }

            return redirect()
                ->route('units.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = 'Gagal mengimpor unit: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }

            return redirect()
                ->route('units.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Download the Excel import template.
     */
    public function downloadImportTemplate()
    {
        return Excel::download(new UnitImportTemplateExport(), 'unit-import-template.xlsx');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit): View
    {
        $unit->load(['unitCategory', 'warehouseArea', 'maintenanceLogs' => function ($query) {
            return $query->with(['operator', 'leader'])->latest()->limit(5);
        }]);

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        $categories = UnitCategory::active()->orderBy('name')->get();
        $areas = WarehouseArea::active()->orderBy('name')->get();
        
        return view('units.edit', compact('unit', 'categories', 'areas'));
    }

    /**
     * Return the next available nomor urut (last number + 1).
     */
    public function nextNomor(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'next_nomor_urut' => Unit::nextNomorUrut(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto_unit')) {
                if ($unit->foto_unit && Storage::disk('public')->exists($unit->foto_unit)) {
                    Storage::disk('public')->delete($unit->foto_unit);
                }
                $data['foto_unit'] = $request->file('foto_unit')->store('units/photos', 'public');
            } else {
                unset($data['foto_unit']);
            }
            
            // Handle QR code regeneration if needed
            if (empty($data['qr_code'])) {
                $data['qr_code'] = 'UNIT-' . strtoupper(uniqid());
            }
            
            $unit->update($data);
            
            return redirect()
                ->route('units.index')
                ->with('success', "Unit '{$unit->nama_unit}' berhasil diperbarui.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui unit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Unit $unit)
    {
        try {
            // Check if unit has related records
            if ($unit->maintenanceLogs()->count() > 0) {
                $message = "Tidak dapat menghapus unit '{$unit->nama_unit}' karena memiliki riwayat maintenance.";

                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return redirect()
                    ->route('units.index')
                    ->with('error', $message);
            }

            if ($unit->redWhiteTags()->count() > 0) {
                $message = "Tidak dapat menghapus unit '{$unit->nama_unit}' karena memiliki tags terkait.";

                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return redirect()
                    ->route('units.index')
                    ->with('error', $message);
            }

            $unit->delete();
            $successMessage = "Unit '{$unit->nama_unit}' berhasil dihapus.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            }

            return redirect()
                ->route('units.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            $errorMessage = 'Gagal menghapus unit: ' . $e->getMessage();

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }

            return redirect()
                ->route('units.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Toggle unit status.
     */
    public function toggleStatus(Unit $unit): JsonResponse
    {
        try {
            $unit->update([
                'is_active' => !$unit->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => "Unit status has been updated successfully.",
                'data' => [
                    'id' => $unit->id,
                    'is_active' => $unit->is_active,
                    'status' => $unit->is_active ? 'active' : 'inactive'
                ]
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update unit status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code for unit.
     */
    public function generateQR(Unit $unit): JsonResponse
    {
        try {
            $qrCode = $unit->qr_code ?? 'UNIT-' . strtoupper(uniqid());
            
            // Update QR code if empty
            if (!$unit->qr_code) {
                $unit->update(['qr_code' => $qrCode]);
            }
            
            return response()->json([
                'success' => true,
                'qr_code' => $qrCode,
                'unit' => $unit
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get units API endpoint for AJAX requests.
     */
    public function api(Request $request): JsonResponse
    {
        $query = Unit::with(['unitCategory', 'warehouseArea']);

        // Apply filters
        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($status = $request->get('status')) {
            $query->byStatus($status);
        }

        $units = $query->orderBy('nama_unit')->get();

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }
}
