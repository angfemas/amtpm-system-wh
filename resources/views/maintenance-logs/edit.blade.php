<x-industrial-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Edit Maintenance Log</h1>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('maintenance-logs.index') }}" icon="arrow-left">
                    Back to Logs
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Edit Maintenance Log Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('maintenance-logs.update', $maintenanceLog) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            @php
                $checklistData = old('checklist_data', $maintenanceLog->checklist_data ?? []);
                $customFieldData = old('custom_field_data', $maintenanceLog->custom_field_data ?? []);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select id="unit_id" 
                                name="unit_id" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Select Unit</option>
                            @foreach(\App\Models\Unit::active()->orderBy('nomor_urut')->get() as $unit)
                                <option value="{{ $unit->id }}" @selected($maintenanceLog->unit_id == $unit->id)>
                                    {{ $unit->nomor_display }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometer_input" class="block text-sm font-medium text-gray-700 mb-2">
                            Kilometer
                        </label>
                        <input type="number" 
                               id="kilometer_input" 
                               name="kilometer_input" 
                               value="{{ old('kilometer_input', $maintenanceLog->kilometer_input) }}"
                               step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter kilometer reading">
                        @error('kilometer_input')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hour_meter_input" class="block text-sm font-medium text-gray-700 mb-2">
                            Hour Meter
                        </label>
                        <input type="number" 
                               id="hour_meter_input" 
                               name="hour_meter_input" 
                               value="{{ old('hour_meter_input', $maintenanceLog->hour_meter_input) }}"
                               step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter hour meter reading">
                        @error('hour_meter_input')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="inline-flex items-center px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $maintenanceLog->status)) }}
                        </div>
                        <input type="hidden" name="status" value="{{ $maintenanceLog->status }}">
                    </div>

                    <div>
                        <label for="tag_type" class="block text-sm font-medium text-gray-700 mb-2">Tag Type</label>
                        <select id="tagTypeSelect"
                                name="tag_type"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="none" @selected(old('tag_type', $maintenanceLog->tag_type ?? 'none') === 'none')>none</option>
                            <option value="red_tag" @selected(old('tag_type', $maintenanceLog->tag_type) === 'red_tag')>red_tag</option>
                            <option value="white_tag" @selected(old('tag_type', $maintenanceLog->tag_type) === 'white_tag')>white_tag</option>
                        </select>
                        @error('tag_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="tagDescriptionWrap" class="{{ old('tag_type', $maintenanceLog->tag_type ?? 'none') === 'none' ? 'hidden' : '' }}">
                        <label for="tag_description" class="block text-sm font-medium text-gray-700 mb-2">Tag Description</label>
                        <textarea
                            id="tag_description"
                            name="tag_description"
                            rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Deskripsikan kerusakan dan estimasi tindak lanjut..."
                        >{{ old('tag_description', $maintenanceLog->tag_description) }}</textarea>
                        @error('tag_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @php $existingTag = $maintenanceLog->redWhiteTags->first(); @endphp
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tag Photo (optional)</label>
                            @if($existingTag && $existingTag->photo_path)
                                <div class="mb-2">
                                    <img src="{{ $existingTag->photo_path ? (str_starts_with($existingTag->photo_path, 'http') ? $existingTag->photo_path : Storage::url($existingTag->photo_path)) : '' }}" class="w-40 h-28 object-cover rounded-lg border border-gray-200" alt="Existing tag photo">
                                </div>
                            @endif
                            <input type="file" name="tag_photo" id="tagPhotoInputEdit" accept="image/*" class="block w-full text-sm text-gray-700" />
                            <div id="tagPhotoPreviewEdit" class="mt-2 grid grid-cols-3 gap-2"></div>
                        </div>
                    </div>

                    <div>
                        <label for="catatan_kerusakan" class="block text-sm font-medium text-gray-700 mb-2">
                            Damage Notes
                        </label>
                        <textarea id="catatan_kerusakan" 
                                  name="catatan_kerusakan" 
                                  rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Enter damage notes">{{ old('catatan_kerusakan', $maintenanceLog->catatan_kerusakan) }}</textarea>
                        @error('catatan_kerusakan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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

                @foreach(old('existing_foto_paths', $maintenanceLog->foto_paths ?? []) as $existingPhoto)
                    <input type="hidden" name="existing_foto_paths[]" value="{{ $existingPhoto }}">
                @endforeach
            </x-industrial-card>

            <x-industrial-card title="Checklist Items">
                @if($checklistItems->isEmpty())
                    <div class="text-sm text-gray-500">Tidak ada checklist items untuk kategori unit ini.</div>
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
                                            {{ data_get($checklistData, "{$item->id}.checked") ? 'checked' : '' }}
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

                                        @if($item->subItems->isNotEmpty())
                                            <div class="mt-3 ml-4 space-y-2 border-l-2 border-gray-200 pl-3">
                                                @foreach($item->subItems->sortBy('urutan') as $subItem)
                                                    <div class="flex items-start gap-2">
                                                        <input type="hidden" name="checklist_data[{{ $item->id }}][sub_items][{{ $subItem->id }}][checked]" value="0">
                                                        <input
                                                            type="checkbox"
                                                            class="h-4 w-4 text-honda-red rounded mt-1 focus:ring-honda-red"
                                                            name="checklist_data[{{ $item->id }}][sub_items][{{ $subItem->id }}][checked]"
                                                            value="1"
                                                            {{ data_get($checklistData, "{$item->id}.sub_items.{$subItem->id}.checked") ? 'checked' : '' }}
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                                            <textarea
                                                name="checklist_data[{{ $item->id }}][notes]"
                                                rows="2"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                            >{{ data_get($checklistData, "{$item->id}.notes") }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-industrial-card>

            <x-industrial-card title="Dynamic Custom Fields">
                @if($customFields->isEmpty())
                    <div class="text-sm text-gray-500">Tidak ada custom fields untuk kategori unit ini.</div>
                @else
                    <div class="space-y-4">
                        @foreach($customFields as $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field->label_field }}
                                    @if($field->is_required)
                                        <span class="text-red-600">*</span>
                                    @endif
                                </label>
                                @php $options = $field->options ?? []; @endphp

                                @if($field->tipe_field === 'textarea')
                                    <textarea
                                        name="custom_field_data[{{ $field->id }}]"
                                        rows="3"
                                        class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >{{ data_get($customFieldData, $field->id) }}</textarea>
                                @elseif($field->tipe_field === 'select')
                                    <select
                                        name="custom_field_data[{{ $field->id }}]"
                                        class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                    >
                                        <option value="">-- pilih --</option>
                                        @foreach((array)$options as $opt)
                                            <option value="{{ $opt }}" @selected(data_get($customFieldData, $field->id) == $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field->tipe_field === 'number')
                                    <input
                                        type="number"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ data_get($customFieldData, $field->id) }}"
                                        class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >
                                @elseif($field->tipe_field === 'date')
                                    <input
                                        type="date"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ data_get($customFieldData, $field->id) }}"
                                        class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                    >
                                @else
                                    <input
                                        type="text"
                                        name="custom_field_data[{{ $field->id }}]"
                                        value="{{ data_get($customFieldData, $field->id) }}"
                                        class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent transition"
                                        placeholder="{{ $field->placeholder }}"
                                    >
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-industrial-card>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <x-industrial-button variant="secondary" type="button" onclick="history.back()">
                    Cancel
                </x-industrial-button>
                <x-industrial-button variant="primary" type="submit">
                    Update Maintenance Log
                </x-industrial-button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tagTypeSelect = document.getElementById('tagTypeSelect');
            const tagDescriptionWrap = document.getElementById('tagDescriptionWrap');

            if (tagTypeSelect && tagDescriptionWrap) {
                tagTypeSelect.addEventListener('change', function () {
                    const show = tagTypeSelect.value !== 'none';
                    tagDescriptionWrap.classList.toggle('hidden', !show);
                });
            }

            const photoInput = document.getElementById('photoInput');
            const photoPreviewGrid = document.getElementById('photoPreviewGrid');
            const existingPhotoInputs = Array.from(document.querySelectorAll('input[name="existing_foto_paths[]"]'));

            let existingPhotos = existingPhotoInputs.map(input => input.value).filter(Boolean);
            let newPhotoItems = [];

            const renderPhotoPreview = () => {
                if (!photoPreviewGrid) return;

                photoPreviewGrid.innerHTML = '';

                existingPhotos.forEach((path, index) => {
                    const thumb = document.createElement('div');
                    thumb.className = 'relative group';
                    thumb.innerHTML = `
                        <img src="${path.startsWith('http') ? path : '/storage/' + path.replace(/^\//, '')}" class="w-full h-24 sm:h-28 object-cover rounded-lg border border-gray-200" alt="existing photo">
                        <button
                            type="button"
                            class="absolute top-2 right-2 bg-black bg-opacity-60 hover:bg-opacity-80 text-white rounded-full p-1.5 sm:p-2 transition-opacity"
                            onclick="removeExistingPhoto(${index})"
                            title="Remove photo"
                        >
                            <i class="bi bi-x-lg text-xs sm:text-sm"></i>
                        </button>
                    `;
                    photoPreviewGrid.appendChild(thumb);
                });

                newPhotoItems.forEach((item, index) => {
                    const thumb = document.createElement('div');
                    thumb.className = 'relative group';
                    thumb.innerHTML = `
                        <img src="${item.dataUrl}" class="w-full h-24 sm:h-28 object-cover rounded-lg border border-gray-200" alt="new photo">
                        <button
                            type="button"
                            class="absolute top-2 right-2 bg-black bg-opacity-60 hover:bg-opacity-80 text-white rounded-full p-1.5 sm:p-2 transition-opacity"
                            onclick="removeNewPhoto(${index})"
                            title="Remove photo"
                        >
                            <i class="bi bi-x-lg text-xs sm:text-sm"></i>
                        </button>
                    `;
                    photoPreviewGrid.appendChild(thumb);
                });
            };

            const syncInputFiles = () => {
                if (!photoInput) return;

                const dataTransfer = new DataTransfer();
                newPhotoItems.forEach(item => dataTransfer.items.add(item.file));
                photoInput.files = dataTransfer.files;
            };

            window.removeExistingPhoto = (idx) => {
                existingPhotos.splice(idx, 1);
                updateExistingPhotoInputs();
                renderPhotoPreview();
            };

            window.removeNewPhoto = (idx) => {
                newPhotoItems.splice(idx, 1);
                syncInputFiles();
                renderPhotoPreview();
            };

            const updateExistingPhotoInputs = () => {
                const container = document.createElement('div');
                existingPhotoInputs.forEach(input => input.remove());
                const form = photoInput?.closest('form');
                if (!form) return;

                existingPhotos.forEach(photo => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'existing_foto_paths[]';
                    hidden.value = photo;
                    form.appendChild(hidden);
                });
            };

            const handleFiles = (files) => {
                const arr = Array.from(files || []);
                if (arr.length === 0) return;

                const remaining = Math.max(0, 3 - existingPhotos.length - newPhotoItems.length);
                const toAdd = arr.slice(0, remaining);

                let loaded = 0;
                toAdd.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        newPhotoItems.push({ file, dataUrl: e.target.result });
                        loaded++;
                        if (loaded === toAdd.length) {
                            syncInputFiles();
                            renderPhotoPreview();
                        }
                    };
                    reader.readAsDataURL(file);
                });
            };

            if (photoInput) {
                photoInput.addEventListener('change', function (e) {
                    handleFiles(e.target.files);
                    photoInput.value = '';
                });
            }

            // Tag photo preview (edit)
            const tagPhotoInputEdit = document.getElementById('tagPhotoInputEdit');
            const tagPhotoPreviewEdit = document.getElementById('tagPhotoPreviewEdit');
            if (tagPhotoInputEdit && tagPhotoPreviewEdit) {
                tagPhotoInputEdit.addEventListener('change', function (e) {
                    tagPhotoPreviewEdit.innerHTML = '';
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'w-full h-20 object-cover rounded-lg border border-gray-200';
                        tagPhotoPreviewEdit.appendChild(img);
                    };
                    reader.readAsDataURL(file);
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

            renderPhotoPreview();
            updateExistingPhotoInputs();
        });
    </script>
</x-industrial-layout>
