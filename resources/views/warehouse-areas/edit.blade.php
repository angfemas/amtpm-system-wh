<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-pencil-square mr-3 text-purple-600"></i>
                    Edit Warehouse Area
                </h1>
                <p class="mt-1 text-sm text-gray-500">Update warehouse area information</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('warehouse-areas.index') }}" icon="arrow-left">
                    Back to Areas
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Edit Area Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('warehouse-areas.update', $warehouseArea) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Area Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               value="{{ old('name', $warehouseArea->name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter warehouse area name">
                        <i class="bi bi-building absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                        Capacity
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="capacity" 
                               name="capacity" 
                               min="1"
                               value="{{ old('capacity', $warehouseArea->capacity) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter area capacity">
                        <i class="bi bi-box absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Enter area description">{{ old('description', $warehouseArea->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Area Color
                    </label>
                    <div class="relative">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', $warehouseArea->color ?? '#28a745') }}"
                               class="w-full h-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $warehouseArea->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <x-industrial-button variant="secondary" type="button" href="{{ route('warehouse-areas.index') }}">
                    Cancel
                </x-industrial-button>
                <x-industrial-button variant="primary" type="submit" icon="save">
                    Update Area
                </x-industrial-button>
            </div>
        </form>
    </div>

    <!-- Area Info -->
    <div class="mt-6 bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Area Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Created At</p>
                <p class="text-sm font-medium text-gray-900">{{ $warehouseArea->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Last Updated</p>
                <p class="text-sm font-medium text-gray-900">{{ $warehouseArea->updated_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Units Count</p>
                <p class="text-sm font-medium text-gray-900">{{ $warehouseArea->units_count ?? $warehouseArea->units()->count() }} units</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <x-industrial-badge status="{{ $warehouseArea->is_active ? 'active' : 'inactive' }}" size="sm" />
            </div>
        </div>
    </div>
</x-industrial-layout>
