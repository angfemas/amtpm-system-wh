<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRedWhiteTagRequest;
use App\Http\Requests\UpdateRedWhiteTagRequest;
use App\Models\RedWhiteTag;
use App\Models\Unit;
use App\Models\MaintenanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedWhiteTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tags = RedWhiteTag::with(['unit', 'maintenanceLog', 'createdBy', 'resolvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('red-white-tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $units = Unit::active()->orderBy('nama_unit')->get();
        return view('red-white-tags.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRedWhiteTagRequest $request): RedirectResponse
    {
        RedWhiteTag::create($request->validated());
        
        return redirect()
            ->route('red-white-tags.index')
            ->with('success', 'Tag berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RedWhiteTag $redWhiteTag): View
    {
        $redWhiteTag->load(['unit', 'maintenanceLog', 'createdBy', 'resolvedBy']);
        return view('red-white-tags.show', compact('redWhiteTag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RedWhiteTag $redWhiteTag): View
    {
        $units = Unit::active()->orderBy('nama_unit')->get();
        return view('red-white-tags.edit', compact('redWhiteTag', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRedWhiteTagRequest $request, RedWhiteTag $redWhiteTag): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->status === 'resolved') {
            $data['actual_resolution_date'] = now();
            $data['resolved_by'] = auth()->id();
        }
        
        $redWhiteTag->update($data);
        
        return redirect()
            ->route('red-white-tags.index')
            ->with('success', 'Tag berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RedWhiteTag $redWhiteTag): RedirectResponse
    {
        $redWhiteTag->delete();
        
        return redirect()
            ->route('red-white-tags.index')
            ->with('success', 'Tag berhasil dihapus.');
    }

    /**
     * Resolve tag
     */
    public function resolve(RedWhiteTag $redWhiteTag): RedirectResponse
    {
        $redWhiteTag->update([
            'status' => 'resolved',
            'actual_resolution_date' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => request('resolution_notes', 'Tag resolved'),
        ]);
        
        return redirect()
            ->route('red-white-tags.index')
            ->with('success', 'Tag berhasil diselesaikan.');
    }

    /**
     * Get tags by type
     */
    public function getByType(string $type): View
    {
        $tags = RedWhiteTag::where('tag_type', $type)
            ->with(['unit', 'maintenanceLog', 'createdBy', 'resolvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('red-white-tags.by-type', compact('tags', 'type'));
    }

    /**
     * Get open tags
     */
    public function getOpen(): View
    {
        $tags = RedWhiteTag::where('status', 'open')
            ->with(['unit', 'maintenanceLog', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('red-white-tags.open', compact('tags'));
    }

    /**
     * Get resolved tags
     */
    public function getResolved(): View
    {
        $tags = RedWhiteTag::where('status', 'resolved')
            ->with(['unit', 'maintenanceLog', 'createdBy', 'resolvedBy'])
            ->orderBy('actual_resolution_date', 'desc')
            ->paginate(10);
        
        return view('red-white-tags.resolved', compact('tags'));
    }

    /**
     * Get overdue tags
     */
    public function getOverdue(): View
    {
        $tags = RedWhiteTag::where('status', 'open')
            ->whereHas('maintenanceLog', function ($query) {
                $query->where('target_resolution_date', '<', now());
            })
            ->with(['unit', 'maintenanceLog', 'createdBy'])
            ->orderBy('target_resolution_date', 'asc')
            ->paginate(10);
        
        return view('red-white-tags.overdue', compact('tags'));
    }
}
