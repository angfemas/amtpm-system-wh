<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-flag-fill mr-3 text-orange-600"></i>
                    Tag Detail
                </h1>
                <p class="mt-1 text-sm text-gray-500">Detail kerusakan untuk {{ $redWhiteTag->isRedTag() ? 'Red Tag' : 'White Tag' }}.</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('red-white-tags.index') }}" icon="arrow-left">
                    Back to Tags
                </x-industrial-button>
                @if($redWhiteTag->status === 'open')
                    <x-industrial-button variant="primary" href="{{ route('red-white-tags.resolve', $redWhiteTag) }}" icon="check2-circle">
                        Resolve Tag
                    </x-industrial-button>
                @endif
            </div>
        </div>
    </x-slot>

    <div x-data="{ imageViewerOpen: false, imageViewerUrl: null, imageViewerAlt: '' }">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="min-w-0">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl {{ $redWhiteTag->isRedTag() ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-700' }}">
                        <i class="bi {{ $redWhiteTag->isRedTag() ? 'bi-x-octagon-fill' : 'bi-tag-fill' }} text-xl"></i>
                    </span>
                    <div class="min-w-0">
                        <h2 class="text-2xl font-bold text-gray-900 truncate">{{ $redWhiteTag->description ?? ($redWhiteTag->isRedTag() ? 'Red Tag Issue' : 'White Tag Issue') }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $redWhiteTag->unit?->kode_unit ?? '-' }} • {{ $redWhiteTag->unit?->nama_unit ?? 'Unit tidak tersedia' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <span class="px-3 py-2 rounded-full text-xs font-semibold {{ $redWhiteTag->isRedTag() ? 'bg-red-900 text-white' : 'bg-gray-900 text-white' }}">
                    {{ $redWhiteTag->isRedTag() ? 'Red Tag' : 'White Tag' }}
                </span>
                <span class="px-3 py-2 rounded-full text-xs font-semibold {{ $redWhiteTag->status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $redWhiteTag->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Informasi Tag</h3>
                <p class="text-sm text-gray-500">Detail ringkas dari kerusakan.</p>
            </div>

            <div class="space-y-4 text-sm text-gray-700">
                <div>
                    <p class="font-medium text-gray-600">Severity</p>
                    <p>{{ $redWhiteTag->severity ?? 'Tidak ditentukan' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Target Resolution</p>
                    <p>{{ $redWhiteTag->target_resolution_date?->format('d M Y') ?? 'Belum diatur' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Actual Resolution</p>
                    <p>{{ $redWhiteTag->actual_resolution_date?->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Created By</p>
                    <p>{{ $redWhiteTag->createdBy?->name ?? 'Unknown' }}</p>
                </div>
                @if($redWhiteTag->status !== 'open')
                    <div>
                        <p class="font-medium text-gray-600">Resolved By</p>
                        <p>{{ $redWhiteTag->resolvedBy?->name ?? 'Unknown' }}</p>
                    </div>
                @endif
                <div>
                    <p class="font-medium text-gray-600">Created At</p>
                    <p>{{ $redWhiteTag->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900">Deskripsi Kerusakan</h3>
                <p class="mt-3 text-gray-700 leading-relaxed">{{ $redWhiteTag->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
            </div>

            @if($redWhiteTag->resolution_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Catatan Penyelesaian</h3>
                    <p class="mt-3 text-gray-700 leading-relaxed">{{ $redWhiteTag->resolution_notes }}</p>
                </div>
            @endif

            @if($redWhiteTag->maintenanceLog)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance Log</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p class="font-medium text-gray-600">Log ID</p>
                            <p>{{ $redWhiteTag->maintenanceLog->id }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600">Tanggal</p>
                            <p>{{ $redWhiteTag->maintenanceLog->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="font-medium text-gray-600">Keterangan</p>
                            <p>{{ $redWhiteTag->maintenanceLog->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($redWhiteTag->photo_path)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Foto Kerusakan</h3>
                    <div class="mt-4 overflow-hidden rounded-xl border border-gray-200">
                        <button type="button"
                                @click="imageViewerUrl = {{ json_encode(str_starts_with($redWhiteTag->photo_path, 'http') ? $redWhiteTag->photo_path : Storage::url($redWhiteTag->photo_path)) }}; imageViewerAlt = 'Photo Tag'; imageViewerOpen = true"
                                class="block w-full">
                            <img src="{{ str_starts_with($redWhiteTag->photo_path, 'http') ? $redWhiteTag->photo_path : Storage::url($redWhiteTag->photo_path) }}" alt="Photo Tag" class="w-full h-auto object-cover">
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div x-show="imageViewerOpen"
             x-cloak
             @keydown.escape.window="imageViewerOpen = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="relative max-w-4xl w-full rounded-3xl overflow-hidden bg-white shadow-2xl">
                <button @click="imageViewerOpen = false"
                        class="absolute top-4 right-4 z-10 text-white bg-black/40 rounded-full p-2 hover:bg-black/60">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img :src="imageViewerUrl" :alt="imageViewerAlt" class="w-full h-auto object-contain max-h-[80vh] bg-black" />
            </div>
        </div>
    </div>
</x-industrial-layout>
