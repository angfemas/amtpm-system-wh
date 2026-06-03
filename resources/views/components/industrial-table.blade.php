@props([
    'headers' => [],
    'data' => [],
    'searchable' => false,
    'searchPlaceholder' => 'Search...',
    'paginated' => false,
    'actions' => true
])

<div class="industrial-card">
    @if($searchable)
        <div class="p-4 border-b border-gray-200">
            <div class="relative">
                <input type="text" 
                       placeholder="{{ $searchPlaceholder }}" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="industrial-table">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    @if($actions)
                        <th class="text-right">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($paginated)
        <div class="p-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of 
                <span class="font-medium">97</span> results
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-honda-red text-white rounded-md">
                    1
                </button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    2
                </button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    3
                </button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
