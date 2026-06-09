<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code-scan mr-3 text-purple-600"></i>
                    QR Scan
                </h1>
                <p class="mt-1 text-sm text-gray-500">Operator-friendly QR scanning untuk membuka unit</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium transition-colors">
                    <i class="bi bi-house mr-2"></i>Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Scan Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('qr-codes.scan') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan QR Code</label>
                <input
                    type="text"
                    name="qr_code"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    placeholder="Contoh: UNIT-ABC123"
                    required
                >
            </div>
            <button type="submit" class="touch-target inline-flex items-center justify-center px-6 py-3 rounded-xl bg-honda-red text-white font-semibold hover:bg-red-700 transition-colors shadow-sm">
                <i class="bi bi-qr-code-scan mr-2"></i>
                Scan
            </button>
        </form>
    </div>

    <!-- Result -->
    @isset($unit)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-14 h-14 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-truck text-purple-700 text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Unit Ditemukan</div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $unit->nomor_nama }}</h2>
                        <div class="mt-1 text-sm text-gray-600">
                            <span class="font-semibold text-gray-900 font-mono">{{ $unit->kode_unit }}</span>
                            • {{ $unit->unitCategory->name ?? '-' }}
                            • {{ $unit->warehouseArea->name ?? '-' }}
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <x-status-badge status="{{ $unit->status }}" :pulse="$unit->isOverdue()" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('units.show', $unit) }}" class="flex items-center justify-center px-5 py-3 rounded-xl font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                        <i class="bi bi-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a
                        href="{{ route('maintenance-logs.create', ['unit_id' => $unit->id]) }}"
                        class="flex items-center justify-center px-5 py-3 rounded-xl font-semibold bg-honda-red hover:bg-red-700 text-white transition-colors shadow-sm"
                    >
                        <i class="bi bi-file-earmark-check mr-2"></i>
                        Isi Checklist
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Kilometer</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->kilometer ?? '-' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Hour Meter</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->hour_meter ?? '-' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Next Due</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">
                        {{ $unit->maintenance_due_date ? $unit->maintenance_due_date->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    @endisset
</x-industrial-layout>

