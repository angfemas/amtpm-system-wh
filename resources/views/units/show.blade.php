<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-truck mr-3 text-blue-600"></i>
                    {{ $unit->nama_unit }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Unit details and information</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('units.index') }}" icon="arrow-left">
                    Back to Units
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('units.edit', $unit) }}" icon="pencil">
                    Edit Unit
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Unit Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mr-4 overflow-hidden">
                        @if($unit->foto_url)
                            <img src="{{ $unit->foto_url }}" alt="{{ $unit->foto_alt }}" class="w-full h-full object-cover">
                        @else
                            <i class="bi bi-truck text-blue-600 text-2xl"></i>
                        @endif
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ $unit->nama_unit }}</h2>
                        <p class="text-blue-100">{{ $unit->kode_unit_formatted }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $unit->status_badge }}">
                        {{ ucfirst($unit->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Code</label>
                            <p class="text-gray-900 font-mono">{{ $unit->kode_unit_formatted }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Category</label>
                            <p class="text-gray-900">{{ $unit->unitCategory?->name ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Warehouse Area</label>
                            <p class="text-gray-900">{{ $unit->warehouseArea?->name ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Maintenance Type</label>
                            <p class="text-gray-900">{{ $unit->jenis_maintenance_display }}</p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Last Maintenance</label>
                            <p class="text-gray-900">
                                {{ $unit->tanggal_maintenance_terakhir?->format('M d, Y') ?? 'Never' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Maintenance Interval</label>
                            <p class="text-gray-900">{{ $unit->interval_hari }} days</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Next Due Date</label>
                            <p class="text-gray-900 {{ $unit->is_overdue ? 'text-red-600 font-semibold' : '' }}">
                                @if($unit->maintenance_due_date)
                                    {{ $unit->maintenance_due_date->format('M d, Y') }}
                                    @if($unit->is_overdue)
                                        <i class="bi bi-exclamation-triangle ml-1"></i>
                                    @endif
                                @else
                                    Not Set
                                @endif
                            </p>
                        </div>
                        @if($unit->is_overdue)
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800">
                                    <i class="bi bi-exclamation-triangle mr-2"></i>
                                    This unit is overdue for maintenance!
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Usage Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Usage Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Kilometer</label>
                            <p class="text-gray-900">{{ number_format($unit->kilometer, 2) }} km</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Hour Meter</label>
                            <p class="text-gray-900">{{ number_format($unit->hour_meter, 2) }} hours</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">QR Code</label>
                            <p class="text-gray-900 font-mono text-sm">{{ $unit->qr_code ?? 'Not Generated' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $unit->status_badge }}">
                                    {{ ucfirst($unit->status) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($unit->keterangan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $unit->keterangan }}</p>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="font-medium text-gray-600">Created At</label>
                        <p class="text-gray-900">{{ $unit->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-600">Last Updated</label>
                        <p class="text-gray-900">{{ $unit->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Maintenance Logs -->
    @if($unit->maintenanceLogs->count() > 0)
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Maintenance Logs</h3>
            <div class="space-y-3">
                @foreach($unit->maintenanceLogs as $log)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-clipboard-check text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $log->submitted_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $log->operator?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $log->status == 'completed' ? 'bg-green-100 text-green-800' : ($log->status == 'submitted' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($unit->maintenanceLogs->count() >= 5)
                <div class="mt-4 text-center">
                    <x-industrial-button variant="secondary" href="{{ route('maintenance-logs.index', ['unit_id' => $unit->id]) }}" icon="list">
                        View All Maintenance Logs
                    </x-industrial-button>
                </div>
            @endif
        </div>
    @else
        <div class="mt-6 bg-gray-50 rounded-xl p-6 text-center">
            <i class="bi bi-clipboard-x text-4xl text-gray-400 mb-4 block"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Maintenance Logs</h3>
            <p class="text-gray-600">This unit doesn't have any maintenance records yet.</p>
            <div class="mt-4">
                <x-industrial-button variant="primary" href="{{ route('maintenance-logs.create', ['unit_id' => $unit->id]) }}" icon="plus-circle">
                    Create First Maintenance Log
                </x-industrial-button>
            </div>
        </div>
    @endif
</x-industrial-layout>
