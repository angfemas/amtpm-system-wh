<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-sliders mr-3 text-teal-600"></i>
                    Custom Fields
                </h1>
                <p class="mt-1 text-sm text-gray-500">Manage custom fields for dynamic forms</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" icon="filter" size="md">
                    Filter
                </x-industrial-button>
                <x-industrial-button variant="primary" icon="plus-circle" size="md">
                    Add Field
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Custom Fields Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($fields as $field)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bi bi-sliders text-teal-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $field->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $field->description ?? 'No description' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $field->category ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-industrial-badge status="{{ $field->type ?? 'text' }}" size="sm" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-industrial-badge status="{{ $field->is_required ? 'warning' : 'success' }}" size="sm" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm">No custom fields found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ $fields->firstItem() }}</span> to 
            <span class="font-medium">{{ $fields->lastItem() }}</span> of 
            <span class="font-medium">{{ $fields->total() }}</span> results
        </div>
        <div class="flex items-center space-x-2">
            {{ $fields->links() }}
        </div>
    </div>
</x-industrial-layout>
