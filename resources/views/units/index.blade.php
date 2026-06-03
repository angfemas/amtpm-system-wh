<x-industrial-layout>
    <x-slot name="header">
        @include('components.toast')
        @unless(class_exists(\ZipArchive::class))
            <div class="mb-4 rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
                <strong>Perhatian:</strong> Ekstensi PHP <code>zip</code> tidak aktif. Impor file <code>.xlsx</code> atau <code>.xls</code> akan gagal sampai ekstensi diaktifkan. Silakan aktifkan <code>php_zip</code> di konfigurasi PHP atau gunakan file <code>.csv</code> untuk sementara.
            </div>
        @endunless
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-truck mr-3 text-blue-600"></i>
                    Units Management
                </h1>
                <p class="mt-1 text-sm text-gray-500">Monitor unit warehouse dan status maintenance</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="hidden sm:block text-sm text-gray-600">
                    Page {{ $units->currentPage() }} / {{ $units->lastPage() }}
                </div>
                <form id="unit-import-form" action="{{ route('units.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <label for="unit-import-file" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                        <i class="bi bi-file-earmark-arrow-up mr-2"></i>
                        Import
                    </label>
                    <input id="unit-import-file" type="file" name="file" accept=".xlsx,.xls,.csv" class="hidden" onchange="document.getElementById('unit-import-submit').disabled = !this.files.length;">
                    <button id="unit-import-submit" type="submit" disabled data-original-text="Upload" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition">
                        Upload
                    </button>
                    <a href="{{ route('units.import.template') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition">
                        <i class="bi bi-download mr-2"></i>
                        Template
                    </a>
                </form>
                <x-industrial-button variant="primary" href="{{ route('units.create') }}" icon="plus-circle" size="md">
                    Add Unit
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    @if($errors->has('file'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-4">
            {{ $errors->first('file') }}
        </div>
    @endif

    <div id="unit-import-status-box" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4 text-sm text-gray-600">
        <div id="unit-import-info">
            <p><strong>Import format:</strong> gunakan header <code>kode_unit</code>, <code>nama_unit</code>, <code>unit_category</code>, <code>warehouse_area</code>, <code>jenis_maintenance</code>, <code>tanggal_maintenance_terakhir</code>, <code>interval_hari</code>, <code>kilometer</code>, <code>hour_meter</code>, <code>status</code>, <code>is_active</code>, <code>keterangan</code>.</p>
            <p class="mt-2 text-xs text-gray-500">Klik tombol <strong>Template</strong> untuk mengunduh file contoh format Excel yang dapat digunakan untuk import. Untuk file XLSX diperlukan ekstensi PHP <code>php_zip</code>.</p>
        </div>
        <div id="unit-import-feedback" class="mt-4 hidden rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700"></div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('units.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Unit code, name..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="relative">
                        <select
                            name="status"
                            class="w-full appearance-none pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <i class="bi bi-funnel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <div class="relative">
                        <select
                            name="category_id"
                            class="w-full appearance-none pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                            <option value="">All Categories</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <i class="bi bi-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Area</label>
                    <div class="relative">
                        <select
                            name="area_id"
                            class="w-full appearance-none pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        >
                            <option value="">All Areas</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}" {{ request('area_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <i class="bi bi-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <x-industrial-button variant="secondary" type="button" href="{{ route('units.index') }}">
                    Clear
                </x-industrial-button>
                <x-industrial-button variant="primary" type="submit" icon="search">
                    Search
                </x-industrial-button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Maintenance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Due</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50 transition-colors" data-unit-id="{{ $unit->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-gray-900">{{ $unit->kode_unit_formatted }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 overflow-hidden">
                                        @if($unit->foto_url)
                                            <img src="{{ $unit->foto_url }}" alt="{{ $unit->foto_alt }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="bi bi-truck text-blue-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $unit->nama_unit }}</div>
                                        <div class="text-xs text-gray-500">{{ $unit->jenis_maintenance_display }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $unit->unitCategory?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-indicator px-2 py-1 text-xs font-medium rounded-full {{ $unit->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $unit->warehouseArea?->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">
                                    {{ $unit->tanggal_maintenance_terakhir?->format('M d, Y') ?? 'Never' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($unit->maintenance_due_date)
                                    <span class="text-sm {{ $unit->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                        {{ $unit->maintenance_due_date->format('M d, Y') }}
                                        @if($unit->is_overdue)
                                            <i class="bi bi-exclamation-triangle ml-1"></i>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Not Set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('units.show', $unit) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('units.edit', $unit) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                                class="text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 p-2 rounded-lg transition-colors cursor-pointer" 
                                                title="{{ $unit->is_active ? 'Deactivate Unit' : 'Activate Unit' }}"
                                                data-unit-id="{{ $unit->id }}"
                                                data-unit-code="{{ $unit->kode_unit }}"
                                                data-unit-name="{{ $unit->nama_unit }}"
                                                data-unit-active="{{ $unit->is_active ? 'true' : 'false' }}"
                                                onclick="toggleUnitStatus(this.dataset.unitId, this.dataset.unitCode, this.dataset.unitName, this.dataset.unitActive)">
                                            <i class="bi {{ $unit->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                        </button>
                                        <button type="button"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete"
                                                data-unit-id="{{ $unit->id }}"
                                                data-unit-code="{{ $unit->kode_unit }}"
                                                data-unit-name="{{ $unit->nama_unit }}"
                                                onclick="deleteUnit(event, this.dataset.unitId, this.dataset.unitCode, this.dataset.unitName)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="bi bi-inbox text-4xl mb-4 block"></i>
                                    <p class="text-lg font-medium">No units found</p>
                                    <p class="text-sm mt-1">Get started by creating your first unit.</p>
                                    <div class="mt-4">
                                        <x-industrial-button variant="primary" href="{{ route('units.create') }}" icon="plus-circle">
                                            Create First Unit
                                        </x-industrial-button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($units->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing {{ $units->firstItem() }} to {{ $units->lastItem() }} of {{ $units->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                @if($units->currentPage() > 1)
                    <a href="{{ $units->previousPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Previous
                    </a>
                @endif

                @for($i = max(1, $units->currentPage() - 2); $i <= min($units->lastPage(), $units->currentPage() + 2); $i++)
                    <a href="{{ $units->url($i) }}" 
                       class="px-3 py-2 text-sm font-medium {{ $i == $units->currentPage() ? 'bg-red-600 text-white' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' }} rounded-lg transition-colors">
                        {{ $i }}
                    </a>
                @endfor

                @if($units->currentPage() < $units->lastPage())
                    <a href="{{ $units->nextPageUrl() }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Next
                    </a>
                @endif
            </div>
        </div>
    @endif
<!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-40 pointer-events-none"></div>
    
    <!-- Toast JavaScript -->
    <script src="{{ asset('js/toast.js') }}"></script>
</x-industrial-layout>
