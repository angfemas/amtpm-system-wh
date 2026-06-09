<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code mr-3 text-purple-600"></i>
                    QR Code - {{ $unit->nomor_nama }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">{{ $unit->unitCategory->name ?? 'Uncategorized' }}</p>
            </div>
            <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="bi bi-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex flex-col items-center">
                <!-- QR Code -->
                <div class="w-64 h-64 bg-white rounded-lg flex items-center justify-center mb-6 border border-gray-200 p-2 [&>svg]:w-full [&>svg]:h-full">
                    @if($unit->qr_code)
                        {!! QrCode::size(250)->margin(0)->generate($unit->qr_code) !!}
                    @else
                        <div class="text-center text-gray-400">
                            <i class="bi bi-qr-code text-6xl"></i>
                            <p class="text-sm mt-2">QR belum tersedia</p>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="w-full space-y-3 text-center">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Identitas</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $unit->nomor_display }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unit</p>
                            <p class="font-mono text-sm font-semibold text-gray-900">{{ $unit->kode_unit }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</p>
                            <p class="font-mono text-sm font-semibold text-gray-900 break-all">{{ $unit->qr_code }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $unit->unitCategory->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Area</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $unit->warehouseArea->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 w-full">
                    <a href="{{ route('qr-codes.download', $unit->id) }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                        <i class="bi bi-download mr-2"></i> Download QR Code
                    </a>
                    <a href="{{ route('units.show', $unit->id) }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">
                        <i class="bi bi-truck mr-2"></i> Detail Unit
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-industrial-layout>
