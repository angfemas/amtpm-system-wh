<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceLogRequest;
use App\Http\Requests\UpdateMaintenanceLogRequest;
use App\Models\ChecklistItem;
use App\Models\CustomField;
use App\Models\MaintenanceLog;
use App\Models\Unit;
use App\Models\RedWhiteTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MaintenanceLog::with(['unit', 'operator', 'leader', 'redWhiteTags']);

        if ($status = $request->get('status')) {
            $query->byStatus($status);
        }

        if ($unitId = $request->get('unit_id')) {
            $query->byUnit($unitId);
        }

        $logs = $query->orderBy('submitted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $units = Unit::active()->orderBy('nama_unit')->get();

        return view('maintenance-logs.index', compact('logs', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $units = Unit::active()->orderBy('nama_unit')->get();
        return view('maintenance-logs.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaintenanceLogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['submitted_at'] = now();
        $data['status'] = 'submitted';
        $data['operator_id'] = auth()->id() ?? null;

        if ($request->hasFile('foto_paths')) {
            $storedPhotos = [];
            foreach ($request->file('foto_paths') as $photo) {
                if ($photo->isValid()) {
                    $storedPhotos[] = $photo->store('maintenance/photos', 'public');
                }
            }
            $data['foto_paths'] = $storedPhotos;
        }
        
        $log = MaintenanceLog::create($data);
        
        // Create red/white tag if needed
        if ($request->tag_type !== 'none' && $request->tag_description) {
            $tagPhotoPath = null;
            if ($request->hasFile('tag_photo') && $request->file('tag_photo')->isValid()) {
                $tagPhotoPath = $request->file('tag_photo')->store('maintenance/tag_photos', 'public');
            }
            RedWhiteTag::create([
                'unit_id' => $data['unit_id'],
                'maintenance_log_id' => $log->id,
                'created_by' => auth()->id() ?? null,
                'photo_path' => $tagPhotoPath,
                'tag_type' => $request->tag_type,
                'description' => $request->tag_description,
                'severity' => $request->tag_type === 'red_tag' ? 'high' : 'medium',
                'status' => 'open',
                'target_resolution_date' => $request->tag_type === 'red_tag' ? now()->addDays(3) : now()->addDay(),
            ]);
        }
        
        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Maintenance log berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceLog $maintenanceLog): View
    {
        $maintenanceLog->load(['unit', 'operator', 'leader', 'redWhiteTags']);

        $categoryId = $maintenanceLog->unit?->unit_category_id;
        $checklistItems = $categoryId
            ? ChecklistItem::query()
                ->whereHas('unitCategories', fn($q) => $q->where('unit_category_id', $categoryId))
                ->with('subItems')
                ->active()
                ->ordered()
                ->get()
            : collect();

        $customFields = $categoryId
            ? CustomField::query()
                ->where('unit_category_id', $categoryId)
                ->active()
                ->ordered()
                ->get()
            : collect();

        return view('maintenance-logs.show', compact('maintenanceLog', 'checklistItems', 'customFields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceLog $maintenanceLog): View
    {
        $units = Unit::active()->orderBy('nama_unit')->get();

        $categoryId = $maintenanceLog->unit?->unit_category_id;
        $checklistItems = $categoryId
            ? ChecklistItem::query()
                ->whereHas('unitCategories', fn($q) => $q->where('unit_category_id', $categoryId))
                ->with('subItems')
                ->active()
                ->ordered()
                ->get()
            : collect();

        $customFields = $categoryId
            ? CustomField::query()
                ->where('unit_category_id', $categoryId)
                ->active()
                ->ordered()
                ->get()
            : collect();

        return view('maintenance-logs.edit', compact('maintenanceLog', 'units', 'checklistItems', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceLogRequest $request, MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->status === 'approved') {
            $data['approved_at'] = now();
            $data['leader_id'] = auth()->id();
        } elseif ($request->status === 'completed') {
            $data['completed_at'] = now();
        }

        $existingPhotos = $request->input('existing_foto_paths', $maintenanceLog->foto_paths ?? []);
        $storedPhotos = is_array($existingPhotos) ? $existingPhotos : [];

        if ($request->hasFile('foto_paths')) {
            foreach ($request->file('foto_paths') as $photo) {
                if ($photo->isValid()) {
                    $storedPhotos[] = $photo->store('maintenance/photos', 'public');
                }
            }
        }

        $data['foto_paths'] = $storedPhotos;
        
        $maintenanceLog->update($data);
        
        // Update red/white tag if needed
        if ($request->tag_type !== 'none' && $request->tag_description) {
            $tagPhotoPath = null;
            if ($request->hasFile('tag_photo') && $request->file('tag_photo')->isValid()) {
                $tagPhotoPath = $request->file('tag_photo')->store('maintenance/tag_photos', 'public');
            }
            $tag = $maintenanceLog->redWhiteTags()->first();
            
            if ($tag) {
                $updateData = [
                    'tag_type' => $request->tag_type,
                    'description' => $request->tag_description,
                    'severity' => $request->tag_type === 'red_tag' ? 'high' : 'medium',
                ];
                if ($tagPhotoPath) {
                    // delete old photo if exists
                    if ($tag->photo_path && Storage::disk('public')->exists($tag->photo_path)) {
                        Storage::disk('public')->delete($tag->photo_path);
                    }
                    $updateData['photo_path'] = $tagPhotoPath;
                }
                $tag->update($updateData);
            } else {
                RedWhiteTag::create([
                    'unit_id' => $maintenanceLog->unit_id,
                    'maintenance_log_id' => $maintenanceLog->id,
                    'created_by' => auth()->id() ?? null,
                    'photo_path' => $tagPhotoPath,
                    'tag_type' => $request->tag_type,
                    'description' => $request->tag_description,
                    'severity' => $request->tag_type === 'red_tag' ? 'high' : 'medium',
                    'status' => 'open',
                    'target_resolution_date' => $request->tag_type === 'red_tag' ? now()->addDays(3) : now()->addDay(),
                ]);
            }
        }
        
        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Maintenance log berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $maintenanceLog->delete();
        
        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Maintenance log berhasil dihapus.');
    }

    /**
     * Approve maintenance log
     */
    public function approve(MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $maintenanceLog->update([
            'status' => 'approved',
            'approved_at' => now(),
            'leader_id' => auth()->id() ?? null,
        ]);
        
        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Maintenance log berhasil disetujui.');
    }

    /**
     * Complete maintenance log
     */
    public function complete(MaintenanceLog $maintenanceLog): RedirectResponse
    {
        $maintenanceLog->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($unit = $maintenanceLog->unit) {
            $unit->update(array_filter([
                'tanggal_maintenance_terakhir' => now(),
                'status' => 'active',
                'kilometer' => $maintenanceLog->kilometer_input,
                'hour_meter' => $maintenanceLog->hour_meter_input,
            ], fn($value) => $value !== null));
        }
        
        // Resolve any open red/white tags
        $maintenanceLog->redWhiteTags()->where('status', 'open')->update([
            'status' => 'resolved',
            'actual_resolution_date' => now(),
            'resolved_by' => auth()->id() ?? null,
            'resolution_notes' => 'Maintenance completed',
        ]);
        
        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Maintenance log berhasil diselesaikan.');
    }

    /**
     * Get maintenance logs by status
     */
    public function getByStatus(string $status): View
    {
        $logs = MaintenanceLog::where('status', $status)
            ->with(['unit', 'operator', 'leader'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);
        
        return view('maintenance-logs.by-status', compact('logs', 'status'));
    }

    /**
     * Get maintenance logs by unit
     */
    public function getByUnit(Unit $unit): View
    {
        $logs = MaintenanceLog::where('unit_id', $unit->id)
            ->with(['unit', 'operator', 'leader'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);
        
        return view('maintenance-logs.by-unit', compact('logs', 'unit'));
    }
}
