<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-building mr-3 text-purple-600"></i>
                    Warehouse Areas
                </h1>
                <p class="mt-1 text-sm text-gray-500">Manage warehouse areas and locations</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button id="filter-toggle-btn" type="button" variant="secondary" icon="filter" size="md" onclick="toggleAreaFilter()">
                    Filter
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('warehouse-areas.create') }}" icon="plus-circle" size="md">
                    Add Area
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div id="area-filter-panel" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 {{ request('search') || request('status') || request('min_capacity') || request('max_capacity') ? '' : 'hidden' }}">
        <form method="GET" action="{{ route('warehouse-areas.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search areas..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        >
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full appearance-none pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <i class="bi bi-funnel absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Capacity</label>
                    <input type="number" name="min_capacity" value="{{ request('min_capacity') }}" class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Min" min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Capacity</label>
                    <input type="number" name="max_capacity" value="{{ request('max_capacity') }}" class="w-full pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Max" min="1">
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('warehouse-areas.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 hover:bg-gray-100 transition">Reset</a>
                <x-industrial-button type="submit" variant="primary" icon="search">Apply</x-industrial-button>
            </div>
        </form>
    </div>

    <!-- Areas Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($areas as $area)
                    <tr class="hover:bg-gray-50 transition-colors" data-area-id="{{ $area->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ $area->color ?? '#28a745' }};">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $area->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $area->description ?? 'No description' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $area->capacity ?? 'Unlimited' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-industrial-badge status="{{ $area->is_active ? 'active' : 'inactive' }}" size="sm" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('warehouse-areas.show', $area) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('warehouse-areas.edit', $area) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="deleteArea(event, this)" data-id="{{ $area->id }}" data-name="{{ $area->name }}" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm">No warehouse areas found</p>
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
            Showing <span class="font-medium">{{ $areas->firstItem() }}</span> to 
            <span class="font-medium">{{ $areas->lastItem() }}</span> of 
            <span class="font-medium">{{ $areas->total() }}</span> results
        </div>
        <div class="flex items-center space-x-2">
            {{ $areas->links() }}
        </div>
    </div>
</x-industrial-layout>

    @include('components.toast')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-40 pointer-events-none"></div>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        function toggleAreaFilter() {
            const panel = document.getElementById('area-filter-panel');
            if (!panel) return;
            panel.classList.toggle('hidden');
        }
    </script>
