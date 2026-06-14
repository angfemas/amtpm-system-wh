<?php

namespace App\Services;

use App\Models\MaintenanceLog;
use App\Models\RedWhiteTag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MaintenanceTagService
{
    /**
     * Create or update a red/white tag for a maintenance log.
     */
    public function syncTag(MaintenanceLog $log, Request $request): void
    {
        if ($request->tag_type === 'none' || !$request->tag_description) {
            return;
        }

        $tagPhotoPath = $this->handleTagPhoto($request);

        $data = [
            'unit_id' => $log->unit_id,
            'maintenance_log_id' => $log->id,
            'created_by' => auth()->id() ?? null,
            'tag_type' => $request->tag_type,
            'description' => $request->tag_description,
            'severity' => $request->tag_type === 'red_tag' ? 'high' : 'medium',
            'status' => 'open',
            'target_resolution_date' => $request->tag_type === 'red_tag' 
                ? now()->addDays(config('maintenance.red_tag_deadline_days', 3)) 
                : now()->addDays(config('maintenance.medium_tag_deadline_days', 1)),
        ];

        if ($tagPhotoPath) {
            $data['photo_path'] = $tagPhotoPath;
        }

        $tag = $log->redWhiteTags()->first();

        if ($tag) {
            if ($tagPhotoPath && $tag->photo_path && Storage::disk('public')->exists($tag->photo_path)) {
                Storage::disk('public')->delete($tag->photo_path);
            }
            $tag->update($data);
        } else {
            RedWhiteTag::create($data);
        }
    }

    protected function handleTagPhoto(Request $request): ?string
    {
        if ($request->hasFile('tag_photo') && $request->file('tag_photo')->isValid()) {
            return $request->file('tag_photo')->store('maintenance/tag_photos', 'public');
        }
        return null;
    }
}
