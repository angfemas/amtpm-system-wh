<x-industrial-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Maintenance Logs</h1>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->can('maintenance.create'))
                    <x-industrial-button variant="primary" href="{{ route('maintenance-logs.create') }}" icon="plus">
                        Create Maintenance Log
                    </x-industrial-button>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Maintenance Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Filters -->
            <form id="maintenanceFilterForm" method="GET" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select id="statusFilter" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">All Status</option>
                        <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Unit</label>
                    <select id="unitFilter" name="unit_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">All Units</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" @selected(request('unit_id') == $unit->id)>{{ $unit->nomor_display }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-3 rounded-xl bg-honda-red text-white font-semibold hover:bg-red-700 transition-colors shadow-sm">
                        Apply Filters
                    </button>
                </div>
            </form>

            <div class="space-y-4 sm:hidden">
                @forelse($logs as $log)
                    <article class="p-4 bg-gray-50 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500">{{ $log->submitted_at ? $log->submitted_at->format('Y-m-d H:i') : '-' }}</p>
                                <h2 class="mt-2 font-semibold text-gray-900">{{ $log->unit ? $log->unit->nomor_display : '-' }}</h2>
                                <div class="mt-1 flex flex-wrap gap-2 items-center">
                                    <p class="text-sm text-gray-600">Operator: {{ $log->operator ? $log->operator->name : '-' }}</p>
                                    @php
                                        $tagTypes = $log->redWhiteTags->pluck('tag_type')->unique();
                                    @endphp
                                    @if($tagTypes->contains('red_tag'))
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Red Tag</span>
                                    @endif
                                    @if($tagTypes->contains('white_tag'))
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-300">White Tag</span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($log->status == 'submitted') bg-yellow-100 text-yellow-800
                                @elseif($log->status == 'approved') bg-blue-100 text-blue-800
                                @elseif($log->status == 'in_progress') bg-orange-100 text-orange-800
                                @elseif($log->status == 'completed') bg-green-100 text-green-800
                                @elseif($log->status == 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('maintenance-logs.show', $log) }}" class="block text-indigo-600 hover:text-indigo-900">View details</a>
                            @if(auth()->user()->can('maintenance.edit'))
                                <a href="{{ route('maintenance-logs.edit', $log) }}" class="block text-blue-600 hover:text-blue-900">Edit</a>
                            @endif
                            @if($log->status == 'submitted' && auth()->user()->can('maintenance.approve'))
                                <a href="{{ route('maintenance-logs.approve', $log) }}" class="block text-green-600 hover:text-green-900">Approve</a>
                            @endif
                            @if($log->status == 'approved' && auth()->user()->can('maintenance.complete'))
                                <a href="{{ route('maintenance-logs.complete', $log) }}" class="block text-purple-600 hover:text-purple-900">Complete</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="text-center text-gray-500">No maintenance logs found.</div>
                @endforelse
            </div>

            <!-- Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->submitted_at ? $log->submitted_at->format('Y-m-d H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $log->unit ? $log->unit->nomor_display : '-' }}</span>
                                        @php
                                            $tagTypes = $log->redWhiteTags->pluck('tag_type')->unique();
                                        @endphp
                                        @if($tagTypes->contains('red_tag'))
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Red Tag</span>
                                        @endif
                                        @if($tagTypes->contains('white_tag'))
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-300">White Tag</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->operator ? $log->operator->name : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($log->status == 'submitted') bg-yellow-100 text-yellow-800
                                        @elseif($log->status == 'approved') bg-blue-100 text-blue-800
                                        @elseif($log->status == 'in_progress') bg-orange-100 text-orange-800
                                        @elseif($log->status == 'completed') bg-green-100 text-green-800
                                        @elseif($log->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('maintenance-logs.show', $log) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        @if(auth()->user()->can('maintenance.edit'))
                                            <a href="{{ route('maintenance-logs.edit', $log) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        @endif
                                        @if($log->status == 'submitted' && auth()->user()->can('maintenance.approve'))
                                            <a href="{{ route('maintenance-logs.approve', $log) }}" class="text-green-600 hover:text-green-900">Approve</a>
                                        @endif
                                        @if($log->status == 'approved' && auth()->user()->can('maintenance.complete'))
                                            <a href="{{ route('maintenance-logs.complete', $log) }}" class="text-purple-600 hover:text-purple-900">Complete</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No maintenance logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- JavaScript for Filters -->
    <script>
        const filterForm = document.getElementById('maintenanceFilterForm');
        document.querySelectorAll('#statusFilter, #unitFilter').forEach((element) => {
            element.addEventListener('change', () => {
                filterForm.submit();
            });
        });
    </script>
</x-industrial-layout>
