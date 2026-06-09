@php
    /** @var \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator $units */
    $unitId = request('unit_id') ?? old('unit_id') ?? ($units?->first()?->id);
    $unit = $units?->firstWhere('id', $unitId) ?? $units?->first();

    $categoryId = $unit?->unit_category_id;

    $checklistItems = $categoryId
        ? \App\Models\ChecklistItem::query()
            ->whereHas('unitCategories', fn($q) => $q->where('unit_category_id', $categoryId))
            ->with('subItems')
            ->active()
            ->ordered()
            ->get()
        : collect();

    $customFields = $categoryId
        ? \App\Models\CustomField::query()
            ->where('unit_category_id', $categoryId)
            ->active()
            ->ordered()
            ->get()
        : collect();

    $totalChecklist = $checklistItems->count();
@endphp

<x-industrial-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <i class="bi bi-file-earmark-check text-2xl sm:text-3xl text-honda-red"></i>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Maintenance Checklist</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Isi checklist maintenance warehouse secara digital</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex px-3 py-1 rounded-full bg-honda-red/10 text-honda-red text-xs font-semibold">
                    <i class="bi bi-list-check mr-1"></i> {{ $totalChecklist }} items
                </span>
                @if($unit)
                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                        <i class="bi bi-truck mr-1"></i> {{ $unit->nomor_display }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <form id="maintenanceForm" method="POST" action="{{ route('maintenance-logs.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <input type="hidden" name="unit_id" value="{{ $unitId }}">
        <input type="hidden" name="status" value="submitted">

        <div class="space-y-6">
            <!-- Section 1: Unit Selection -->
            <x-industrial-card title="Select Unit" icon="truck">
                <div class="space-y-4">
                    <!-- Desktop: Searchable table -->
                    <div class="hidden sm:block">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Search or select unit</label>
                        <input type="text" id="unitSearch" placeholder="Cari kode/nama unit..." class="w-full px-4 py-2 mb-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition" />
                        <div class="overflow-y-auto max-h-72 border border-gray-200 rounded-lg bg-gray-50">
                            <table class="min-w-full text-sm text-left">
                                <tbody id="unitTableBody" class="divide-y divide-gray-200">
                                    @foreach($units as $u)
                                        <tr class="cursor-pointer hover:bg-honda-red/5 transition {{ $u->id == $unitId ? 'bg-honda-red/10 font-bold' : '' }}" data-id="{{ $u->id }}">
                                            <td class="px-4 py-3">
                                                <div class="font-mono text-xs text-gray-500">{{ $u->kode_unit }}</div>
                                                <div class="font-semibold text-gray-900">{{ $u->nomor_display }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Mobile dropdown -->
                    <div class="sm:hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Unit</label>
                        <select id="unitSelectMobile" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" @selected($u->id == $unitId)>{{ $u->kode_unit }} - {{ $u->nomor_display }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('unit_id')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </x-industrial-card>

            <!-- Section 2: Unit Status -->
            @if($unit)
                <x-industrial-card title="Unit Status" icon="info-circle">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase font-semibold">Next Due Date</p>
                            <p class="mt-2 text-lg font-bold text-gray-900">
                                {{ $unit->maintenance_due_date ? $unit->maintenance_due_date->format('d M Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase font-semibold">Maintenance Status</p>
                            <div class="mt-2">
                                <x-status-badge status="{{ $unit->status }}" :pulse="$unit->is_overdue ?? false" />
                            </div>
                        </div>
                    </div>
                </x-industrial-card>
            @endif

            <!-- Section 3: Checklist Progress -->
            <x-industrial-card title="Progress">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-900">Checklist Items</span>
                        <span class="text-sm font-bold text-honda-red" id="checklistProgressText">0%</span>
                    </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                id="checklistProgressBar"
                                class="bg-gradient-to-r from-honda-red to-repsol-orange h-2 rounded-full transition-all duration-300"
                                style="width: 0%"
                            ></div>
                        </div>
                    </div>
                </x-industrial-card>

            <!-- Section 4: Checklist Items -->
            <x-industrial-card title="Checklist Items" icon="check-list">
                    @if($checklistItems->isEmpty())
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-900">
                            Belum ada checklist untuk kategori unit ini.
                        </div>
                        <!-- Agar validasi checklist_data tetap lulus -->
                        <input type="hidden" name="checklist_data[0][checked]" value="0">
                    @else
                        <div class="space-y-3">
                            @foreach($checklistItems as $item)
                                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl hover:border-gray-200 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="pt-1">
                                            <input type="hidden" name="checklist_data[{{ $item->id }}][checked]" value="0">
                                            <input
                                                type="checkbox"
                                                class="h-5 w-5 text-honda-red rounded focus:ring-honda-red"
                                                name="checklist_data[{{ $item->id }}][checked]"
                                                value="1"
                                                onchange="updateChecklistProgress()"
                                            >
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-3">
                                                <label class="text-sm font-semibold text-gray-900 cursor-pointer">
                                                    {{ $item->nama_item }}
                                                </label>
                                                @if($item->is_required)
                                                    <span class="text-xs font-bold px-2 py-1 rounded-lg bg-red-50 text-red-700 border border-red-200">
                                                        Required
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!empty($item->deskripsi))
                                                <p class="text-xs text-gray-500 mt-1">{{ $item->deskripsi }}</p>
                                            @endif

                                            <!-- Sub-Items Display -->
                                            @if($item->subItems && $item->subItems->count() > 0)
                                                <div class="mt-3 ml-4 space-y-2 border-l-2 border-gray-200 pl-3">
                                                    @foreach($item->subItems->sortBy('urutan') as $subItem)
                                                        <div class="flex items-start gap-2">
                                                            <input 
                                                                type="hidden" 
                                                                name="checklist_data[{{ $item->id }}][sub_items][{{ $subItem->id }}][checked]" 
                                                                value="0"
                                                            >
                                                            <input
                                                                type="checkbox"
                                                                class="h-4 w-4 text-honda-red rounded mt-0.5 focus:ring-honda-red"
                                                                name="checklist_data[{{ $item->id }}][sub_items][{{ $subItem->id }}][checked]"
                                                                value="1"
                                                                onchange="updateChecklistProgress()"
                                                            >
                                                            <div class="flex-1 min-w-0">
                                                                <label class="text-xs font-medium text-gray-700 cursor-pointer">
                                                                    {{ $subItem->judul }}
                                                                </label>
                                                                @if(!empty($subItem->deskripsi))
                                                                    <p class="text-xs text-gray-500">{{ $subItem->deskripsi }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mt-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Catatan (opsional)
                                                </label>
                                                <textarea
                                                    name="checklist_data[{{ $item->id }}][notes]"
                                                    rows="2"
                                                    placeholder="Tambahkan catatan untuk checklist ini..."
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-industrial-card>

            <!-- Section 5: Photo Documentation -->
            <x-industrial-card title="Photo Documentation (Max 3)" icon="image">
                <div
                    id="photoDropzone"
                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-honda-red hover:bg-red-50 transition-all cursor-pointer"
                    onclick="document.getElementById('photoInput').click()"
                >
                    <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-3 block"></i>
                    <div class="font-semibold text-gray-800">Drag & drop or click to upload</div>
                    <div class="text-xs text-gray-500 mt-1">PNG/JPG up to 10MB per file</div>
                    <input
                        id="photoInput"
                        name="foto_paths[]"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        multiple
                    >
                </div>

                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3" id="photoPreviewGrid"></div>
            </x-industrial-card>

            <!-- Section 6: Damage Notes -->
            <x-industrial-card title="Damage Notes" icon="exclamation-triangle">
                <textarea
                    name="catatan_kerusakan"
                    rows="4"
                    placeholder="Jelaskan kerusakan/temuan yang perlu ditangani..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                >{{ old('catatan_kerusakan') }}</textarea>
                @error('catatan_kerusakan')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </x-industrial-card>

            <!-- Section 7: Maintenance Inputs -->
            <x-industrial-card title="Maintenance Inputs" icon="calculator">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kilometer Input</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="kilometer_input"
                            value="{{ old('kilometer_input') }}"
                            placeholder="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                        >
                        @error('kilometer_input')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hour Meter Input</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="hour_meter_input"
                            value="{{ old('hour_meter_input') }}"
                            placeholder="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                        >
                        @error('hour_meter_input')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </x-industrial-card>

            <!-- Section 8: Red / White Tag -->
            <x-industrial-card title="Red / White Tag" icon="tag">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tag Type</label>
                        <select
                            id="tagTypeSelect"
                            name="tag_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                        >
                            <option value="none" @selected(old('tag_type', 'none') === 'none')>-- No Tag --</option>
                            <option value="red_tag" @selected(old('tag_type') === 'red_tag')>Red Tag (Danger)</option>
                            <option value="white_tag" @selected(old('tag_type') === 'white_tag')>White Tag (Caution)</option>
                        </select>
                        @error('tag_type')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="tagDescriptionWrap" class="{{ old('tag_type', 'none') === 'none' ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tag Description</label>
                        <textarea
                            name="tag_description"
                            rows="3"
                            placeholder="Deskripsikan kerusakan dan estimasi tindak lanjut..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                        >{{ old('tag_description') }}</textarea>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tag Photo (optional)</label>
                            <input type="file" name="tag_photo" id="tagPhotoInput" accept="image/*" class="block w-full text-sm text-gray-700" />
                            <div id="tagPhotoPreview" class="mt-2 grid grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Section 9: Dynamic Custom Fields -->
            <x-industrial-card title="Dynamic Custom Fields" icon="sliders">
                @if($customFields->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-500">
                        <i class="bi bi-inbox text-2xl text-gray-400 mb-2 block"></i>
                        Tidak ada custom fields untuk kategori ini
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($customFields as $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field->label_field }}
                                    @if($field->is_required)
                                        <span class="text-red-600 font-bold">*</span>
                                    @endif
                                </label>
                                @php $options = $field->options ?? []; @endphp

                                @if($field->tipe_field === 'textarea')
                                    <textarea
                                        name="custom_field_data[{{ $field->id }}]"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >{{ old("custom_field_data.$field->id") }}</textarea>
                                @elseif($field->tipe_field === 'select')
                                    <select
                                        name="custom_field_data[{{ $field->id }}]"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                    >
                                        <option value="">-- pilih --</option>
                                        @foreach((array)$options as $opt)
                                            <option value="{{ $opt }}" @selected(old("custom_field_data.$field->id") == $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field->tipe_field === 'number')
                                    <input
                                        type="number"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ old("custom_field_data.$field->id") }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >
                                @elseif($field->tipe_field === 'date')
                                    <input
                                        type="date"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ old("custom_field_data.$field->id") }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                    >
                                @else
                                    <input
                                        type="text"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ old("custom_field_data.$field->id") }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-industrial-card>

            <!-- Section 10: Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-gray-200">
                @if($unit)
                    <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold transition-colors">
                        <i class="bi bi-x-lg mr-2"></i>
                        Batal
                    </a>
                @else
                    <a href="{{ route('units.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold transition-colors">
                        <i class="bi bi-x-lg mr-2"></i>
                        Batal
                    </a>
                @endif
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-lg bg-honda-red hover:bg-red-700 active:bg-red-800 text-white font-semibold transition-colors shadow-md">
                    <i class="bi bi-check-circle mr-2"></i>
                    Submit Checklist
                </button>
            </div>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Unit Search & Selection =====
        const unitSearch = document.getElementById('unitSearch');
        const unitTableBody = document.getElementById('unitTableBody');
        const unitSelectMobile = document.getElementById('unitSelectMobile');

        // Desktop: Search table
        if (unitSearch && unitTableBody) {
            unitSearch.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                Array.from(unitTableBody.rows).forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
            // Click row to navigate
            Array.from(unitTableBody.rows).forEach(row => {
                row.addEventListener('click', function() {
                    const unitId = row.getAttribute('data-id');
                    if (unitId) window.location = '{{ request()->url() }}?unit_id=' + unitId;
                });
            });
        }

        // Mobile: Select dropdown
        if (unitSelectMobile) {
            unitSelectMobile.addEventListener('change', function() {
                if (this.value) window.location = '{{ request()->url() }}?unit_id=' + this.value;
            });
        }

        // ===== Checklist Progress =====
        const updateChecklistProgress = () => {
            const total = document.querySelectorAll('input[type="checkbox"][name^="checklist_data["]').length;
            const checked = document.querySelectorAll('input[type="checkbox"][name^="checklist_data["]:checked').length;
            const progress = total ? Math.round((checked / total) * 100) : 0;
            const bar = document.getElementById('checklistProgressBar');
            const text = document.getElementById('checklistProgressText');
            if (bar) bar.style.width = progress + '%';
            if (text) text.textContent = progress + '%';
        };
        document.querySelectorAll('input[type="checkbox"][name^="checklist_data["]').forEach(checkbox => {
            checkbox.addEventListener('change', updateChecklistProgress);
        });
        updateChecklistProgress();

        // ===== Tag Description Toggle =====
        const tagTypeSelect = document.getElementById('tagTypeSelect');
        const tagDescriptionWrap = document.getElementById('tagDescriptionWrap');
        if (tagTypeSelect) {
            tagTypeSelect.addEventListener('change', () => {
                tagDescriptionWrap?.classList.toggle('hidden', tagTypeSelect.value === 'none');
            });
        }

        // ===== Tag Photo Preview =====
        const tagPhotoInput = document.getElementById('tagPhotoInput');
        const tagPhotoPreview = document.getElementById('tagPhotoPreview');
        if (tagPhotoInput && tagPhotoPreview) {
            tagPhotoInput.addEventListener('change', (e) => {
                tagPhotoPreview.innerHTML = '';
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'w-full h-20 object-cover rounded-lg border border-gray-200';
                    tagPhotoPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        // ===== Photo Upload Handler =====
        let fotoItems = [];

        const syncPhotoInputFiles = () => {
            const photoInput = document.getElementById('photoInput');
            if (!photoInput) return;

            const dataTransfer = new DataTransfer();
            fotoItems.forEach(item => dataTransfer.items.add(item.file));
            photoInput.files = dataTransfer.files;
            renderFotoPreview();
        };

        const renderFotoPreview = () => {
            const grid = document.getElementById('photoPreviewGrid');
            if (!grid) return;

            grid.innerHTML = '';

            fotoItems.forEach((item, idx) => {
                const thumb = document.createElement('div');
                thumb.className = 'relative group';
                thumb.innerHTML = `
                    <img src="${item.dataUrl}" class="w-full h-24 sm:h-28 object-cover rounded-lg border border-gray-200" alt="photo">
                    <button
                        type="button"
                        class="absolute top-2 right-2 bg-black bg-opacity-60 hover:bg-opacity-80 text-white rounded-full p-1.5 sm:p-2 transition-opacity"
                        onclick="removeFoto(${idx})"
                        title="Remove photo"
                    >
                        <i class="bi bi-x-lg text-xs sm:text-sm"></i>
                    </button>
                `;
                grid.appendChild(thumb);
            });
        };

        window.removeFoto = (idx) => {
            fotoItems = fotoItems.filter((_, i) => i !== idx);
            syncPhotoInputFiles();
        };

        const handleFiles = (files) => {
            const arr = Array.from(files || []);
            if (arr.length === 0) return;

            const remaining = Math.max(0, 3 - fotoItems.length);
            const toAdd = arr.slice(0, remaining);

            let done = 0;
            toAdd.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    fotoItems.push({ file, dataUrl: e.target.result });
                    done++;
                    if (done === toAdd.length) syncPhotoInputFiles();
                };
                reader.readAsDataURL(file);
            });
        };

        const photoInput = document.getElementById('photoInput');
        if (photoInput) {
            photoInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
                photoInput.value = '';
            });
        }

        const dropzone = document.getElementById('photoDropzone');
        if (dropzone) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-honda-red', 'bg-red-50');
            });
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-honda-red', 'bg-red-50');
            });
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-honda-red', 'bg-red-50');
                handleFiles(e.dataTransfer.files);
            });
        }
    });
    </script>
</x-industrial-layout>

