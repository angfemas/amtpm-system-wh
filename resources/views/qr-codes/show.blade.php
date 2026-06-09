<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code mr-3 text-purple-600"></i>
                    QR Code - {{ $unit->nomor_nama }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Preview and download QR code for this unit</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('qr-codes.index') }}" icon="arrow-left">
                    Back
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('qr-codes.download', $unit->id) }}" icon="download">
                    Download QR Code
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 flex flex-col items-center">
                <div class="w-64 h-64 bg-white rounded-lg flex items-center justify-center border border-gray-200 p-3 [&>svg]:w-full [&>svg]:h-full">
                    {!! $qrSvg !!}
                </div>

                <div class="mt-6 w-full grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="text-sm text-gray-600">Kode Unit</div>
                        <div class="mt-1 text-lg font-bold text-gray-900 font-mono break-all">{{ $unit->kode_unit }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="text-sm text-gray-600">QR Code</div>
                        <div class="mt-1 text-lg font-bold text-gray-900 font-mono break-all">{{ $unit->qr_code }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="text-sm text-gray-600">Kategori</div>
                        <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->unitCategory->name ?? 'N/A' }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="text-sm text-gray-600">Area</div>
                        <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->warehouseArea->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <x-industrial-button variant="primary" href="{{ route('qr-codes.download', $unit->id) }}" icon="download">
                        Download QR Code
                    </x-industrial-button>
                    <x-industrial-button variant="secondary" href="{{ route('units.show', $unit->id) }}" icon="eye">
                        Lihat Detail Unit
                    </x-industrial-button>
                </div>
            </div>
        </div>
    </div>
</x-industrial-layout>
