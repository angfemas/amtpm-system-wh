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
                <x-industrial-button type="button" variant="secondary" icon="filter" size="md" x-data x-on:click="$dispatch('toggle-qr-filter')">
                    Filter
                </x-industrial-button>
                <x-industrial-button variant="primary" icon="download" size="md">
                    Export All
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Filters -->
    @php($filtersActive = request()->filled('search') || request()->filled('category_id'))
    <div
        x-data="{ open: {{ $filtersActive ? 'true' : 'false' }} }"
        x-on:toggle-qr-filter.window="open = !open"
        x-show="open"
        x-cloak
        x-transition
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
    >
        <form method="GET" action="{{ route('qr-codes.index') }}" id="qr-filter-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Unit number, code, name..."
                            autocomplete="off"
                            oninput="clearTimeout(window.__qrFilterTimer); window.__qrFilterTimer = setTimeout(() => this.form.submit(), 400);"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        >
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <div class="relative">
                        <select
                            name="category_id"
                            onchange="this.form.submit()"
                            class="w-full appearance-none pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        >
                            <option value="">All Categories</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ (string) request('category_id') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <i class="bi bi-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end gap-3">
                @if($filtersActive)
                    <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition">
                        <i class="bi bi-x-circle mr-2"></i>
                        Clear
                    </a>
                @endif
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                    <i class="bi bi-funnel mr-2"></i>
                    Apply
                </button>
            </div>
        </form>
    </div>

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
                    <h3 class="text-lg font-semibold text-gray-900">{{ $unit->nomor_nama }}</h3>
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
