<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-flag-fill mr-3 text-orange-600"></i>
                    Red Tag & White Tag
                </h1>
                <p class="mt-1 text-sm text-gray-500">Monitoring kerusakan berat (red) & ringan (white) untuk maintenance warehouse</p>
            </div>
            <div class="flex items-center space-x-2">
                @php
                    $openRed = collect($tags->items())->where('tag_type', 'red_tag')->where('status', 'open')->count();
                    $openWhite = collect($tags->items())->where('tag_type', 'white_tag')->where('status', 'open')->count();
                @endphp
                <span class="px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-bold">
                    Red open: {{ $openRed }}
                </span>
                <span class="px-3 py-2 rounded-xl bg-gray-100 border border-gray-200 text-gray-800 text-sm font-bold">
                    White open: {{ $openWhite }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="text-sm text-gray-600">
            Menampilkan <span class="font-bold text-gray-900">{{ $tags->total() }}</span> tag (pagination server-side).
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tags as $tag)
            @include('red-white-tags.partials.tag-card', ['tag' => $tag])
        @empty
            <div class="md:col-span-2 lg:col-span-3 text-center py-10 text-gray-500">
                <i class="bi bi-inbox text-4xl text-gray-300 mb-2 d-block"></i>
                Tidak ada data tag.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tags->links() }}
    </div>
</x-industrial-layout>

