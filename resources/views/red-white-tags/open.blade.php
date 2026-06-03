<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-bell-fill mr-3 text-orange-600"></i>
                    Open Tags
                </h1>
                <p class="mt-1 text-sm text-gray-500">Tag yang belum terselesaikan</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tags as $tag)
            @include('red-white-tags.partials.tag-card', ['tag' => $tag])
        @empty
            <div class="md:col-span-2 lg:col-span-3 text-center py-10 text-gray-500">
                <i class="bi bi-inbox text-4xl text-gray-300 mb-2 d-block"></i>
                Tidak ada open tag.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tags->links() }}
    </div>
</x-industrial-layout>

