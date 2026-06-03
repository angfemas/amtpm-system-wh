<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-eye mr-3 text-purple-600"></i>
                    {{ $warehouseArea->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Warehouse area details and associated units</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('warehouse-areas.index') }}" icon="arrow-left">
                    Back to Areas
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('warehouse-areas.edit', $warehouseArea) }}" icon="pencil">
                    Edit Area
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Area Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: {{ $warehouseArea->color ?? '#28a745' }};">
                        <i class="bi bi-building text-white text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $warehouseArea->name }}</h2>
                    <x-industrial-badge status="{{ $warehouseArea->is_active ? 'active' : 'inactive' }}" size="sm" class="mt-2" />
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-sm text-gray-900">{{ $warehouseArea->description ?? 'No description available' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Capacity</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $warehouseArea->capacity ?? 'Unlimited' }}</p>
                        @if($warehouseArea->capacity)
                        <div class="mt-2">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Occupancy</span>
                                <span>{{ $warehouseArea->units_count ?? $warehouseArea->units()->count() }} / {{ $warehouseArea->capacity }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(($warehouseArea->units_count ?? $warehouseArea->units()->count()) / $warehouseArea->capacity * 100, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Color</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded" style="background-color: {{ $warehouseArea->color ?? '#28a745' }};"></div>
                            <span class="text-sm text-gray-900">{{ $warehouseArea->color ?? '#28a745' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm text-gray-900">{{ $warehouseArea->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="mt-6 space-y-2">
                    <x-industrial-button variant="primary" href="{{ route('warehouse-areas.edit', $warehouseArea) }}" icon="pencil" fullWidth>
                        Edit Area
                    </x-industrial-button>
                    <form action="{{ route('warehouse-areas.toggle-status', $warehouseArea) }}" method="POST">
                        @csrf
                        <x-industrial-button variant="{{ $warehouseArea->is_active ? 'warning' : 'success' }}" type="submit" icon="{{ $warehouseArea->is_active ? 'pause' : 'play' }}" fullWidth>
                            {{ $warehouseArea->is_active ? 'Deactivate' : 'Activate' }}
                        </x-industrial-button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <!-- Units in this Area -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Units in this Area</h3>
                    <p class="text-sm text-gray-500">All units located in {{ $warehouseArea->name }}</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
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
                                    <div class="text-sm font-medium text-gray-900">{{ $unit->nama_unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $unit->unitCategory->name ?? 'Unknown' }}
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
                                    <p class="text-sm">No units found in this area</p>
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
