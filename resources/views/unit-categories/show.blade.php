<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-eye mr-3 text-green-600"></i>
                    {{ $unitCategory->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Category details and associated units</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('unit-categories.index') }}" icon="arrow-left">
                    Back to Categories
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('unit-categories.edit', $unitCategory) }}" icon="pencil">
                    Edit Category
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Category Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: {{ $unitCategory->color ?? '#007bff' }};">
                        <i class="bi bi-tags text-white text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $unitCategory->name }}</h2>
                    <x-industrial-badge status="{{ $unitCategory->is_active ? 'active' : 'inactive' }}" size="sm" class="mt-2" />
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-sm text-gray-900">{{ $unitCategory->description ?? 'No description available' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Color</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded" style="background-color: {{ $unitCategory->color ?? '#007bff' }};"></div>
                            <span class="text-sm text-gray-900">{{ $unitCategory->color ?? '#007bff' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Total Units</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $unitCategory->units_count ?? $unitCategory->units()->count() }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm text-gray-900">{{ $unitCategory->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="mt-6 space-y-2">
                    <x-industrial-button variant="primary" href="{{ route('unit-categories.edit', $unitCategory) }}" icon="pencil" fullWidth>
                        Edit Category
                    </x-industrial-button>
                    <form action="{{ route('unit-categories.toggle-status', $unitCategory) }}" method="POST">
                        @csrf
                        <x-industrial-button variant="{{ $unitCategory->is_active ? 'warning' : 'success' }}" type="submit" icon="{{ $unitCategory->is_active ? 'pause' : 'play' }}" fullWidth>
                            {{ $unitCategory->is_active ? 'Deactivate' : 'Activate' }}
                        </x-industrial-button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <!-- Units in this Category -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Units in this Category</h3>
                    <p class="text-sm text-gray-500">All units belonging to {{ $unitCategory->name }}</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($units as $unit)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-medium text-gray-900">{{ $unit->kode_unit }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $unit->nomor_nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $unit->warehouseArea->name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-industrial-badge status="{{ $unit->status }}" size="sm" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('units.show', $unit) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('units.edit', $unit) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">No units found in this category</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($units, 'links'))
                <div class="p-4 border-t border-gray-200">
                    {{ $units->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-industrial-layout>
