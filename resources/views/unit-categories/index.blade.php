<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-tags mr-3 text-green-600"></i>
                    Unit Categories
                </h1>
                <p class="mt-1 text-sm text-gray-500">Manage unit categories and classifications</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button id="filter-toggle-btn" type="button" variant="secondary" icon="filter" size="md" onclick="toggleCategoryFilter()">
                    Filter
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('unit-categories.create') }}" icon="plus-circle" size="md">
                    Add Category
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div id="category-filter-panel" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 {{ request('search') || request('status') ? '' : 'hidden' }}">
        <form method="GET" action="{{ route('unit-categories.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search categories..."
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
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <i class="bi bi-funnel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div class="flex items-end">
                    <div class="w-full flex justify-end space-x-3">
                        <a href="{{ route('unit-categories.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 hover:bg-gray-100 transition">
                            Reset
                        </a>
                        <x-industrial-button type="submit" variant="primary" icon="search">
                            Apply
                        </x-industrial-button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors" data-category-id="{{ $category->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" 
                                     style="background-color: {{ $category->color }}33;">
                                    <i class="bi bi-tags" style="color: {{ $category->color }};"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $category->description ?? 'No description' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $category->units_count ?? 0 }} units
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-industrial-badge status="{{ $category->is_active ? 'active' : 'inactive' }}" size="sm" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('unit-categories.show', $category) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('unit-categories.edit', $category) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('unit-categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm">No categories found</p>
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
            Showing <span class="font-medium">{{ $categories->firstItem() }}</span> to 
            <span class="font-medium">{{ $categories->lastItem() }}</span> of 
            <span class="font-medium">{{ $categories->total() }}</span> results
        </div>
        <div class="flex items-center space-x-2">
            {{ $categories->links() }}
        </div>
    </div>
    {{-- Toast container and script for AJAX actions --}}
    @include('components.toast')
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        function toggleCategoryFilter() {
            const panel = document.getElementById('category-filter-panel');
            if (!panel) return;
            panel.classList.toggle('hidden');
        }
    </script>
</x-industrial-layout>
