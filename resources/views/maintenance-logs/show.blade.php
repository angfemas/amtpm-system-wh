<x-industrial-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Maintenance Log Details</h1>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('maintenance-logs.index') }}" icon="arrow-left">
                    Back to Logs
                </x-industrial-button>
                @if(auth()->user()->can('maintenance.edit'))
                    <x-industrial-button variant="primary" href="{{ route('maintenance-logs.edit', $maintenanceLog) }}" icon="edit">
                        Edit
                    </x-industrial-button>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Maintenance Log Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Log Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->unit ? $maintenanceLog->unit->nomor_display : '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submitted Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->submitted_at ? $maintenanceLog->submitted_at->format('Y-m-d H:i') : '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($maintenanceLog->status == 'submitted') bg-yellow-100 text-yellow-800
                                    @elseif($maintenanceLog->status == 'approved') bg-blue-100 text-blue-800
                                    @elseif($maintenanceLog->status == 'in_progress') bg-orange-100 text-orange-800
                                    @elseif($maintenanceLog->status == 'completed') bg-green-100 text-green-800
                                    @elseif($maintenanceLog->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($maintenanceLog->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personnel</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Operator</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->operator ? $maintenanceLog->operator->name : '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Leader</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->leader ? $maintenanceLog->leader->name : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kilometer</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->kilometer_input ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hour Meter</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->hour_meter_input ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $maintenanceLog->catatan_kerusakan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($maintenanceLog->foto_paths)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Photos</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $photos = is_string($maintenanceLog->foto_paths)
                                        ? json_decode($maintenanceLog->foto_paths, true)
                                        : $maintenanceLog->foto_paths;
                                    $photos = is_array($photos) ? $photos : [];
                                @endphp
                                @foreach($photos as $photo)
                                    <img src="{{ Storage::url($photo) }}" alt="Maintenance Photo" class="w-full h-32 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <x-industrial-card title="Checklist Items">
                    @php
                        $checklistData = $maintenanceLog->checklist_data ?? [];
                    @endphp
                    @if($checklistItems->isEmpty())
                        <div class="text-sm text-gray-500">Tidak ada checklist items untuk kategori unit ini.</div>
                    @else
                        <div class="space-y-3">
                            @foreach($checklistItems as $item)
                                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1">
                                            @if(data_get($checklistData, "{$item->id}.checked"))
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-800">
                                                    <i class="bi bi-check-lg"></i>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-500">
                                                    <i class="bi bi-circle"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <h4 class="font-semibold text-gray-900">{{ $item->nama_item }}</h4>
                                                @if($item->is_required)
                                                    <span class="text-xs font-bold px-2 py-1 rounded-lg bg-red-50 text-red-700 border border-red-200">Required</span>
                                                @endif
                                            </div>
                                            @if(!empty($item->deskripsi))
                                                <p class="text-sm text-gray-500 mt-1">{{ $item->deskripsi }}</p>
                                            @endif

                                            @if($item->subItems->isNotEmpty())
                                                <div class="mt-3 ml-4 space-y-2 border-l-2 border-gray-200 pl-3">
                                                    @foreach($item->subItems->sortBy('urutan') as $subItem)
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-600">
                                                                @if(data_get($checklistData, "{$item->id}.sub_items.{$subItem->id}.checked"))
                                                                    <i class="bi bi-check"></i>
                                                                @else
                                                                    <i class="bi bi-circle"></i>
                                                                @endif
                                                            </span>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-700">{{ $subItem->judul }}</p>
                                                                @if(!empty($subItem->deskripsi))
                                                                    <p class="text-xs text-gray-500">{{ $subItem->deskripsi }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if(data_get($checklistData, "{$item->id}.notes"))
                                                <div class="mt-3 p-3 rounded-xl bg-white border border-gray-200 text-sm text-gray-700">
                                                    <strong class="text-gray-900">Catatan:</strong>
                                                    <p>{{ data_get($checklistData, "{$item->id}.notes") }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-industrial-card>
            </div>

            <div class="mt-6">
                <x-industrial-card title="Dynamic Custom Fields">
                    @if($customFields->isEmpty())
                        <div class="text-sm text-gray-500">Tidak ada custom fields untuk kategori unit ini.</div>
                    @else
                        <div class="space-y-4">
                            @foreach($customFields as $field)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field->label_field }}</label>
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm text-gray-800">
                                        {{ data_get($maintenanceLog->custom_field_data, $field->id) ?? '-' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-industrial-card>
            </div>

            <!-- Red/White Tags -->
            @if($maintenanceLog->redWhiteTags && $maintenanceLog->redWhiteTags->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Tags</h3>
                    <div class="space-y-3">
                        @foreach($maintenanceLog->redWhiteTags as $tag)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($tag->tag_type === 'red_tag') bg-red-100 text-red-800
                                            @else bg-white text-gray-800 border border-gray-300 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $tag->tag_type)) }}
                                        </span>
                                            <p class="mt-2 text-sm text-gray-900">{{ $tag->description }}</p>
                                            @if($tag->photo_path)
                                                <div class="mt-2">
                                                    <img src="{{ str_starts_with($tag->photo_path, 'http') ? $tag->photo_path : Storage::url($tag->photo_path) }}" alt="Tag Photo" class="w-48 h-32 object-cover rounded-lg border border-gray-200">
                                                </div>
                                            @endif
                                        <p class="mt-1 text-xs text-gray-500">Severity: {{ $tag->severity }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($tag->status === 'open') bg-yellow-100 text-yellow-800
                                        @elseif($tag->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($tag->status === 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($tag->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                @if($maintenanceLog->status == 'submitted' && auth()->user()->can('maintenance.approve'))
                    <x-industrial-button variant="success" href="{{ route('maintenance-logs.approve', $maintenanceLog) }}" icon="check">
                        Approve
                    </x-industrial-button>
                @endif
                @if($maintenanceLog->status == 'approved' && auth()->user()->can('maintenance.complete'))
                    <x-industrial-button variant="primary" href="{{ route('maintenance-logs.complete', $maintenanceLog) }}" icon="check-circle">
                        Complete
                    </x-industrial-button>
                @endif
            </div>
        </div>
    </div>
</x-industrial-layout>
