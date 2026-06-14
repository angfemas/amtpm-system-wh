<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Unit;
use App\Models\RedWhiteTag;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $overdueUnits = Unit::with('unitCategory')
            ->active()
            ->whereNotNull('tanggal_maintenance_terakhir')
            ->where('interval_hari', '>', 0)
            ->get()
            ->filter(fn (Unit $u) => $u->is_overdue);

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
            'pendingCompletionLogs',
            'overdueUnits'
        ));
    }

    /**
     * Get dashboard data as JSON.
     */
    public function data(): array
    {
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
            'overdueCount' => $overdueCount,
            'maintenanceTodayCount' => $maintenanceTodayCount,
            'redTagCount' => $redTagCount,
            'whiteTagCount' => $whiteTagCount,
            'maintenanceMonthCount' => $maintenanceMonthCount,
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

    /**
     * Export full dashboard report as CSV.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'dashboard_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Report Type', 'Total Units', 'Active Units', 'Overdue Maintenance', 'Maintenance Today', 'Red Tags', 'White Tags', 'Maintenance This Month', 'Generated At']);

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

            fputcsv($file, [
                'Dashboard Summary',
                $totalUnits,
                $activeUnits,
                $overdueCount,
                $maintenanceTodayCount,
                $redTagCount,
                $whiteTagCount,
                $maintenanceMonthCount,
                now()->format('Y-m-d H:i:s'),
            ]);

            fputcsv($file, []);
            fputcsv($file, ['Recent Activities']);
            fputcsv($file, ['Activity', 'Unit', 'Operator', 'Status', 'Submitted At']);

            $recentLogs = MaintenanceLog::with(['unit', 'operator'])
                ->orderBy('submitted_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($recentLogs as $log) {
                fputcsv($file, [
                    'Maintenance',
                    $log->unit?->nomor_display ?? 'N/A',
                    $log->operator->name ?? 'N/A',
                    $log->status,
                    $log->submitted_at ? $log->submitted_at->format('Y-m-d H:i:s') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export daily dashboard report as CSV.
     */
    public function exportDaily(): StreamedResponse
    {
        $fileName = 'daily_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Daily Report - ' . now()->format('Y-m-d')]);
            fputcsv($file, []);
            fputcsv($file, ['Metric', 'Count']);
            fputcsv($file, ['Total Units', Unit::count()]);
            fputcsv($file, ['Active Units', Unit::where('is_active', true)->count()]);
            fputcsv($file, ['Maintenance Today', MaintenanceLog::whereDate('submitted_at', now()->toDateString())->count()]);
            fputcsv($file, ['Overdue Maintenance', Unit::active()
                ->whereNotNull('tanggal_maintenance_terakhir')
                ->where('interval_hari', '>', 0)
                ->get()
                ->filter(fn (Unit $u) => $u->is_overdue)
                ->count()]);
            fputcsv($file, ['Open Red Tags', RedWhiteTag::where('tag_type', 'red_tag')->where('status', 'open')->count()]);
            fputcsv($file, ['Open White Tags', RedWhiteTag::where('tag_type', 'white_tag')->where('status', 'open')->count()]);

            fputcsv($file, []);
            fputcsv($file, ['Today\'s Maintenance Activities']);
            fputcsv($file, ['Unit', 'Operator', 'Status', 'Submitted At', 'Kilometer', 'Hour Meter']);

            $todayLogs = MaintenanceLog::with(['unit', 'operator'])
                ->whereDate('submitted_at', now()->toDateString())
                ->orderBy('submitted_at', 'desc')
                ->get();

            foreach ($todayLogs as $log) {
                fputcsv($file, [
                    $log->unit?->nomor_display ?? 'N/A',
                    $log->operator->name ?? 'N/A',
                    $log->status,
                    $log->submitted_at ? $log->submitted_at->format('H:i:s') : '-',
                    $log->kilometer_input ?? '-',
                    $log->hour_meter_input ?? '-',
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['Overdue Units']);
            fputcsv($file, ['Unit', 'Category', 'Last Maintenance', 'Due Date', 'Days Overdue']);

            $overdueUnits = Unit::with('unitCategory')
                ->active()
                ->whereNotNull('tanggal_maintenance_terakhir')
                ->where('interval_hari', '>', 0)
                ->get()
                ->filter(fn (Unit $u) => $u->is_overdue);

            foreach ($overdueUnits as $unit) {
                $dueDate = $unit->maintenance_due_date;
                $daysOverdue = $dueDate ? $dueDate->diffInDays(now(), false) : 0;

                fputcsv($file, [
                    $unit->nomor_display,
                    $unit->unitCategory->name ?? 'Uncategorized',
                    $unit->tanggal_maintenance_terakhir ? $unit->tanggal_maintenance_terakhir->format('Y-m-d') : 'N/A',
                    $dueDate ? $dueDate->format('Y-m-d') : 'N/A',
                    $daysOverdue,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
