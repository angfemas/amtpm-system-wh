@php
    $recent = $recentLogs ?? collect();
    
    // Optimized chart data generation
    $dailyLabels = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('M d'))->toArray();
    $dailyCounts = collect(range(6, 0))->map(function($i) use ($recent) {
        $date = now()->subDays($i)->toDateString();
        return $recent->filter(fn($l) => $l->submitted_at && $l->submitted_at->toDateString() === $date)->count();
    })->toArray();

    $weeklyLabels = collect(range(0, 3))->map(function($w) {
        return now()->startOfWeek()->subWeeks(3)->addWeeks($w)->format('M d');
    })->toArray();
    
    $weeklyCounts = collect(range(0, 3))->map(function($w) use ($recent) {
        $weekStart = now()->startOfWeek()->subWeeks(3)->addWeeks($w);
        $weekEnd = $weekStart->copy()->endOfWeek();
        return $recent->filter(fn($l) => $l->submitted_at && $l->submitted_at->between($weekStart, $weekEnd))->count();
    })->toArray();

    $monthlyLabels = collect(range(0, 5))->map(function($m) {
        return now()->startOfMonth()->subMonths(5)->addMonths($m)->format('M');
    })->toArray();
    
    $monthlyCounts = collect(range(0, 5))->map(function($m) use ($recent) {
        $monthStart = now()->startOfMonth()->subMonths(5)->addMonths($m);
        $monthEnd = $monthStart->copy()->endOfMonth();
        return $recent->filter(fn($l) => $l->submitted_at && $l->submitted_at->between($monthStart, $monthEnd))->count();
    })->toArray();

    $yearlyLabels = collect(range(now()->year - 4, now()->year))->map(fn($y) => (string) $y)->toArray();
    $yearlyCounts = collect(range(now()->year - 4, now()->year))->map(function($y) use ($recent) {
        $yearStart = now()->copy()->setYear($y)->startOfYear();
        $yearEnd = $yearStart->copy()->endOfYear();
        return $recent->filter(fn($l) => $l->submitted_at && $l->submitted_at->between($yearStart, $yearEnd))->count();
    })->toArray();

    $unitsByCategory = $unitsByCategory ?? [];
    $categoryLabels = collect($unitsByCategory)->pluck('category')->values()->all();
    $categoryCounts = collect($unitsByCategory)->pluck('count')->map(fn($c) => (int) $c)->values()->all();
@endphp

<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-speedometer2 mr-3 text-red-600"></i>
                    Warehouse Dashboard
                </h1>
                <p class="mt-1 text-sm text-gray-500">Industrial maintenance monitoring untuk warehouse PT Astra Honda Motor</p>
            </div>

            <div class="flex items-center space-x-3">
                <div class="relative">
                    <select id="dashboardRangeSelect" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <i class="bi bi-calendar3 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm hover:shadow-md">
                    <i class="bi bi-download mr-2"></i>
                    Export Report
                </button>
            </div>
        </div>
    </x-slot>

    @if(($overdueCount ?? 0) > 0)
        <x-notification-toast
            type="overdue"
            title="Overdue Maintenance"
            message="Ada {{ $overdueCount }} item maintenance yang melewati target."
            :show="true"
        />
    @endif

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Units -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Unit</p>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $totalUnits ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ $activeUnits ?? 0 }} active</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">{{ ($totalUnits ?? 0) - ($activeUnits ?? 0) }} inactive</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-truck text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Overdue Maintenance</p>
                    <p class="text-3xl font-bold text-red-600 mb-2">{{ $overdueCount ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-red-600 font-medium">Critical</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">Action required</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <i class="bi bi-exclamation-triangle-fill text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Maintenance Today -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Maintenance Hari Ini</p>
                    <p class="text-3xl font-bold text-green-600 mb-2">{{ $maintenanceTodayCount ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">On schedule</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">Current day activities</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-wrench text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Red Tags -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Jumlah Red Tag</p>
                    <p class="text-3xl font-bold text-red-600 mb-2">{{ $redTagCount ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-red-600 font-medium">Workshop handling</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">Heavy repair</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-700 to-red-900 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-x-octagon-fill text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- White Tags -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Jumlah White Tag</p>
                    <p class="text-3xl font-bold text-gray-800 mb-2">{{ $whiteTagCount ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-gray-700 font-medium">Internal handling</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">Light maintenance</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-700 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-tag-fill text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Maintenance Month -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Maintenance Bulan Ini</p>
                    <p class="text-3xl font-bold text-orange-600 mb-2">{{ $maintenanceMonthCount ?? 0 }}</p>
                    <div class="flex items-center text-sm">
                        <span class="text-orange-600 font-medium">Monthly activities</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span class="text-gray-500">Planning reference</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-calendar3 text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Maintenance Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Maintenance Overview</h2>
                <div class="flex items-center space-x-2">
                    <button data-range="daily" class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors bg-red-50 text-red-700" id="rangeDaily">
                        Daily
                    </button>
                    <button data-range="weekly" class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" id="rangeWeekly">
                        Weekly
                    </button>
                    <button data-range="monthly" class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" id="rangeMonthly">
                        Monthly
                    </button>
                    <button data-range="yearly" class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" id="rangeYearly">
                        Yearly
                    </button>
                </div>
            </div>

            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="maintenanceChart"></canvas>
            </div>
        </div>

        <!-- Units by Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Units by Category</h2>
                <span class="text-xs text-gray-500 font-semibold">{{ count($categoryLabels) }} categories</span>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activities</h2>
                <span class="text-sm text-gray-500">
                    Last {{ $recent->count() }} records
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="bi bi-wrench text-blue-500 mr-2"></i>
                                    Maintenance
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->unit->nomor_display ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->operator->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge status="{{ $log->status }}" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->submitted_at ? $log->submitted_at->diffForHumans() : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                <div>No recent activities found</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-industrial-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ranges = {
            daily: {
                labels: @json($dailyLabels),
                counts: @json($dailyCounts)
            },
            weekly: {
                labels: @json($weeklyLabels),
                counts: @json($weeklyCounts)
            },
            monthly: {
                labels: @json($monthlyLabels),
                counts: @json($monthlyCounts)
            },
            yearly: {
                labels: @json($yearlyLabels),
                counts: @json($yearlyCounts)
            }
        };

        const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
        const maintenanceChart = new Chart(maintenanceCtx, {
            type: 'line',
            data: {
                labels: ranges.daily.labels,
                datasets: [{
                    label: 'Maintenance',
                    data: ranges.daily.counts,
                    borderColor: 'rgb(230, 0, 18)',
                    backgroundColor: 'rgba(230, 0, 18, 0.12)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        const updateRangeUI = (rangeKey) => {
            const buttons = document.querySelectorAll('button[data-range]');
            buttons.forEach(btn => {
                const active = btn.dataset.range === rangeKey;
                btn.classList.toggle('bg-red-50', active);
                btn.classList.toggle('text-red-700', active);
            });
        };

        const setRange = (rangeKey) => {
            const r = ranges[rangeKey] || ranges.daily;
            maintenanceChart.data.labels = r.labels;
            maintenanceChart.data.datasets[0].data = r.counts;
            maintenanceChart.update();
            updateRangeUI(rangeKey);
        };

        document.querySelectorAll('button[data-range]').forEach(btn => {
            btn.addEventListener('click', () => setRange(btn.dataset.range));
        });

        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryCounts),
                    backgroundColor: [
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(107, 114, 128)',
                        'rgb(99, 102, 241)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>

