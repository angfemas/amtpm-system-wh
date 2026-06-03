@php
    $isRed = ($tag->tag_type ?? '') === 'red_tag';
    $cardClasses = $isRed
        ? 'bg-red-50 border border-red-200'
        : 'bg-gray-100 border border-gray-200';

    $typeBadgeClasses = $isRed
        ? 'bg-red-900 text-white'
        : 'bg-gray-800 text-white';
@endphp

<div class="{{ $cardClasses }} rounded-xl p-4 hover:border-opacity-80 transition-colors">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <i class="bi {{ $isRed ? 'bi-x-octagon-fill text-red-700' : 'bi-tag-fill text-gray-600' }}"></i>
                <h3 class="text-sm font-bold text-gray-900 truncate">
                    {{ $tag->description ?? ($isRed ? 'Red issue' : 'White issue') }}
                </h3>
            </div>
            <div class="text-xs text-gray-600 mt-2">
                Unit: <span class="font-semibold text-gray-900">{{ $tag->unit?->kode_unit ?? '-' }}</span>
                <span class="text-gray-400">•</span>
                <span>{{ $tag->unit?->nama_unit ?? '' }}</span>
            </div>
        </div>

        <span class="px-2.5 py-1.5 rounded-lg text-xs font-bold {{ $typeBadgeClasses }}">
            {{ $isRed ? 'Red Tag' : 'White Tag' }}
        </span>
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
        <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-white border border-gray-200 text-gray-800">
            Status: {{ ucfirst(str_replace('_', ' ', $tag->status)) }}
        </span>
        <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-white border border-gray-200 text-gray-800">
            Severity: {{ $tag->severity ?? '-' }}
        </span>
    </div>

    <div class="mt-3 text-xs text-gray-600">
        @if($tag->target_resolution_date)
            Target: <span class="font-semibold text-gray-900">{{ $tag->target_resolution_date->format('d M Y') }}</span>
        @endif
        @if($tag->actual_resolution_date)
            <span class="text-gray-400">•</span>
            Actual: <span class="font-semibold text-gray-900">{{ $tag->actual_resolution_date->format('d M Y') }}</span>
        @endif
    </div>

    @if(($tag->status ?? '') === 'open')
        <div class="mt-4">
            <a
                href="{{ route('red-white-tags.resolve', $tag) }}"
                class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl font-semibold bg-honda-red hover:bg-red-700 text-white transition-colors shadow-sm"
            >
                <i class="bi bi-check2-circle mr-2"></i>
                Resolve Tag
            </a>
        </div>
    @endif
</div>

