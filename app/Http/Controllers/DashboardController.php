<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Unit;
use App\Models\RedWhiteTag;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // Get statistics (optimized with single queries)
        $stats = [
            'totalUnits' => Unit::count(),
            'activeUnits' => Unit::where('is_active', true)->count(),
            'maintenanceLogs' => MaintenanceLog::count(),
            'pendingLogs' => MaintenanceLog::where('status', 'submitted')->count(),
            'openTags' => RedWhiteTag::where('status', 'open')->count(),
            'overdueTags' => RedWhiteTag::where('status', 'open')
                ->whereNotNull('target_resolution_date')
                ->whereDate('target_resolution_date', '<', now()->toDateString())
                ->count(),
        ];

        // Get recent activities (optimized)
        $recentLogs = MaintenanceLog::with(['unit', 'operator'])
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        // Get units by category (optimized)
        $unitsByCategory = Unit::with('unitCategory')
            ->active()
            ->get()
            ->groupBy('unit_category_id')
            ->map(function ($group) {
                return [
                    'category' => optional($group->first()?->unitCategory)->name ?? 'Uncategorized',
                    'count' => $group->count(),
                ];
            })
            ->values();

        // Get maintenance by status (optimized)
        $maintenanceByStatus = [
            'submitted' => MaintenanceLog::where('status', 'submitted')->count(),
            'approved' => MaintenanceLog::where('status', 'approved')->count(),
            'completed' => MaintenanceLog::where('status', 'completed')->count(),
            'overdue' => MaintenanceLog::where('status', 'overdue')->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'recentLogs',
            'unitsByCategory',
            'maintenanceByStatus'
        ));
    }

    /**
     * Get dashboard data as JSON.
     */
    public function data(): array
    {
        $totalUnits = Unit::count();
        $activeUnits = Unit::where('is_active', true)->count();
        $maintenanceLogs = MaintenanceLog::count();
        $pendingLogs = MaintenanceLog::where('status', 'submitted')->count();
        $openTags = RedWhiteTag::where('status', 'open')->count();
        $overdueTags = RedWhiteTag::where('status', 'open')
            ->whereNotNull('target_resolution_date')
            ->whereDate('target_resolution_date', '<', now()->toDateString())
            ->count();

        return [
            'totalUnits' => $totalUnits,
            'activeUnits' => $activeUnits,
            'maintenanceLogs' => $maintenanceLogs,
            'pendingLogs' => $pendingLogs,
            'openTags' => $openTags,
            'overdueTags' => $overdueTags,
        ];
    }

    /**
     * Get maintenance chart data.
     */
    public function maintenanceChart(): array
    {
        return MaintenanceLog::query()
            ->whereNotNull('submitted_at')
            ->whereBetween('submitted_at', [now()->subDays(30)->startOfDay(), now()->endOfDay()])
            ->selectRaw('DATE(submitted_at) as day, COUNT(*) as count')
            ->groupByRaw('DATE(submitted_at)')
            ->orderByRaw('DATE(submitted_at)')
            ->get()
            ->map(fn ($row) => [
                'day' => $row->day,
                'count' => (int) $row->count,
            ])
            ->values()
            ->all();
    }

    /**
     * Get units by category chart data.
     */
    public function unitsByCategoryChart(): array
    {
        return Unit::query()
            ->with('unitCategory')
            ->active()
            ->get()
            ->groupBy('unit_category_id')
            ->map(fn ($group) => [
                'category' => optional($group->first()?->unitCategory)->name ?? 'Uncategorized',
                'count' => $group->count(),
            ])
            ->values()
            ->all();
    }
}
