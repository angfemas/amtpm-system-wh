<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-card-checklist mr-3 text-indigo-600"></i>
                    Checklist Item Detail
                </h1>
                <p class="mt-1 text-sm text-gray-500">Review the checklist item information and metadata.</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('checklist-items.index') }}" icon="arrow-left">
                    Back to Checklist
                </x-industrial-button>
                <x-industrial-button variant="primary" href="{{ route('checklist-items.edit', $checklistItem) }}" icon="pencil">
                    Edit Item
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Item Name</h2>
                        <p class="mt-2 text-lg font-semibold text-gray-900">{{ $checklistItem->nama_item }}</p>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Description</h2>
                        <p class="mt-2 text-sm text-gray-700">{{ $checklistItem->deskripsi ?? 'No description provided' }}</p>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Unit Categories</h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($checklistItem->unitCategories as $category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <p class="text-sm text-gray-500">No categories assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Type</h2>
                        <x-industrial-badge status="{{ $checklistItem->tipe }}" size="sm" />
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Status</h2>
                        <x-industrial-badge status="{{ $checklistItem->is_active ? 'active' : 'inactive' }}" size="sm" />
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Required</h2>
                        <x-industrial-badge status="{{ $checklistItem->is_required ? 'warning' : 'success' }}" size="sm" />
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Order</h2>
                        <p class="mt-2 text-sm text-gray-700">{{ $checklistItem->urutan }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-gray-50 rounded-2xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Options</h3>
                @if(!empty($checklistItem->options) && is_array($checklistItem->options))
                    <div class="mt-3 grid grid-cols-1 gap-2">
                        @foreach($checklistItem->options as $option)
                            <span class="inline-flex items-center px-3 py-2 rounded-full bg-white border border-gray-200 text-sm text-gray-700">{{ $option }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-sm text-gray-500">No options configured.</p>
                @endif
            </div>

            <!-- Sub-Items Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i class="bi bi-list-check mr-2"></i>
                    Sub-Items
                </h3>
                @forelse($checklistItem->subItems->sortBy('urutan') as $subItem)
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-indigo-200 p-5 mb-3 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900">{{ $subItem->judul }}</h4>
                                @if($subItem->deskripsi)
                                    <p class="mt-1 text-sm text-gray-600">{{ $subItem->deskripsi }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-white border border-indigo-200 text-xs font-medium text-indigo-700 ml-3">
                                Order: {{ $subItem->urutan }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                        <i class="bi bi-inbox text-3xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">No sub-items configured</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 flex flex-col sm:flex-row sm:justify-between gap-3 border-t pt-6">
                <x-industrial-button variant="secondary" href="{{ route('checklist-items.index') }}" type="button">
                    Back
                </x-industrial-button>
                <form action="{{ route('checklist-items.destroy', $checklistItem) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-industrial-button variant="danger" type="submit" icon="trash" onclick="return confirm('Hapus item checklist ini?')">
                        Delete Item
                    </x-industrial-button>
                </form>
            </div>
        </div>
    </div>
</x-industrial-layout>
