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
        // Statistic cards
        $totalUnits = Unit::count();
        $activeUnits = Unit::where('is_active', true)->count();

        $overdueCount = Unit::active()
            ->whereNotNull('tanggal_maintenance_terakhir')
            ->where('interval_hari', '>', 0)
            ->get()
            ->filter(fn (Unit $u) => $u->is_overdue)
            ->count();

        $maintenanceTodayCount = MaintenanceLog::whereDate('submitted_at', now()->toDateString())->count();

        $redTagCount = RedWhiteTag::where('tag_type', 'red_tag')->where('status', 'open')->count();
        $whiteTagCount = RedWhiteTag::where('tag_type', 'white_tag')->where('status', 'open')->count();

        $maintenanceMonthCount = MaintenanceLog::whereYear('submitted_at', now()->year)
            ->whereMonth('submitted_at', now()->month)
            ->count();

        // Get recent activities
        $recentLogs = MaintenanceLog::with(['unit', 'operator'])
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        // Get units by category
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

        // Maintenance by status
        $maintenanceByStatus = [
            'submitted' => MaintenanceLog::where('status', 'submitted')->count(),
            'approved' => MaintenanceLog::where('status', 'approved')->count(),
            'completed' => MaintenanceLog::where('status', 'completed')->count(),
            'overdue' => MaintenanceLog::where('status', 'overdue')->count(),
        ];

        // Pending approval/completion for admin & leader
        $user = auth()->user();
        $pendingApprovalLogs = collect();
        $pendingCompletionLogs = collect();

        if ($user && ($user->can('maintenance.approve') || $user->can('maintenance.complete'))) {
            if ($user->can('maintenance.approve')) {
                $pendingApprovalLogs = MaintenanceLog::with(['unit', 'operator'])
                    ->where('status', 'submitted')
                    ->orderBy('submitted_at', 'desc')
                    ->limit(10)
                    ->get();
            }

            if ($user->can('maintenance.complete')) {
                $pendingCompletionLogs = MaintenanceLog::with(['unit', 'operator', 'leader'])
                    ->where('status', 'approved')
                    ->orderBy('approved_at', 'desc')
                    ->limit(10)
                    ->get();
            }
        }

        return view('dashboard', compact(
            'totalUnits',
            'activeUnits',
            'overdueCount',
            'maintenanceTodayCount',
            'redTagCount',
            'whiteTagCount',
            'maintenanceMonthCount',
            'recentLogs',
            'unitsByCategory',
            'maintenanceByStatus',
            'pendingApprovalLogs',
            'pendingCompletionLogs'
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
