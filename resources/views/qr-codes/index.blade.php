<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code mr-3 text-purple-600"></i>
                    QR Codes Management
                </h1>
                <p class="mt-1 text-sm text-gray-500">Generate and manage QR codes for units</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" icon="filter" size="md">
                    Filter
                </x-industrial-button>
                <x-industrial-button variant="primary" icon="download" size="md">
                    Export All
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- QR Codes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($units as $unit)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex flex-col items-center">
                <!-- QR Code Placeholder -->
                <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4 border-2 border-dashed border-gray-300">
                    <i class="bi bi-qr-code text-4xl text-gray-400"></i>
                </div>
                
                <!-- Unit Info -->
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $unit->nama_unit }}</h3>
                    <p class="text-sm text-gray-500">{{ $unit->unitCategory->name ?? 'Uncategorized' }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col space-y-2 w-full">
                    <x-industrial-button variant="primary" href="{{ route('qr-codes.show', $unit->id) }}" icon="eye" size="sm" fullWidth>
                        View QR Code
                    </x-industrial-button>
                    <x-industrial-button variant="secondary" href="{{ route('qr-codes.download', $unit->id) }}" icon="download" size="sm" fullWidth>
                        Download
                    </x-industrial-button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-500 mb-2">No QR codes found</p>
            <p class="text-sm text-gray-400">Generate QR codes for units to see them here</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($units) && method_exists($units, 'links'))
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ $units->firstItem() }}</span> to 
            <span class="font-medium">{{ $units->lastItem() }}</span> of 
            <span class="font-medium">{{ $units->total() }}</span> results
        </div>
        <div class="flex items-center space-x-2">
            {{ $units->links() }}
        </div>
    </div>
    @endif
</x-industrial-layout>
