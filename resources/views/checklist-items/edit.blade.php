<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-pencil-square mr-3 text-indigo-600"></i>
                    Edit Checklist Item
                </h1>
                <p class="mt-1 text-sm text-gray-500">Update the checklist item details, sub-items and save your changes.</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('checklist-items.index') }}" icon="arrow-left">
                    Back to Checklist
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('checklist-items.update', $checklistItem) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Categories <span class="text-red-500">*</span></label>
                        
                        <!-- Search Input -->
                        <div class="relative mb-3">
                            <input 
                                type="text" 
                                id="categorySearch" 
                                placeholder="Search atau ketik kategori..." 
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                autocomplete="off"
                            />
                            <i class="bi bi-search absolute right-4 top-3.5 text-gray-400"></i>
                        </div>

                        <!-- Dropdown Results -->
                        <div id="categoryDropdown" class="hidden absolute z-50 w-96 bg-white rounded-xl border border-gray-300 shadow-lg mt-1">
                            <div id="categoryList" class="max-h-60 overflow-y-auto">
                                <!-- Categories will be populated here -->
                            </div>
                        </div>

                        <!-- Selected Categories -->
                        <div id="selectedCategories" class="flex flex-wrap gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 min-h-12">
                            @forelse($checklistItem->unitCategories as $category)
                                <div class="selectedCategory-{{ $category->id }} flex items-center gap-2 bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <span>{{ $category->name }}</span>
                                    <button type="button" class="text-indigo-600 hover:text-indigo-800 font-bold" onclick="removeCategory({{ $category->id }})">×</button>
                                    <input type="hidden" name="unit_category_ids[]" value="{{ $category->id }}">
                                </div>
                            @empty
                                <p id="emptyText" class="text-gray-400 text-sm">Pilih kategori...</p>
                            @endforelse
                        </div>

                        @error('unit_category_ids')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_item" class="block text-sm font-medium text-gray-700 mb-2">Item Name <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_item" name="nama_item" value="{{ old('nama_item', $checklistItem->nama_item) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="e.g. Cleaning" />
                        @error('nama_item')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Optional description">{{ old('deskripsi', $checklistItem->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                        <select id="tipe" name="tipe" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="checkbox" {{ old('tipe', $checklistItem->tipe) === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="text" {{ old('tipe', $checklistItem->tipe) === 'text' ? 'selected' : '' }}>Text</option>
                            <option value="number" {{ old('tipe', $checklistItem->tipe) === 'number' ? 'selected' : '' }}>Number</option>
                            <option value="select" {{ old('tipe', $checklistItem->tipe) === 'select' ? 'selected' : '' }}>Select</option>
                        </select>
                        @error('tipe')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="options" class="block text-sm font-medium text-gray-700 mb-2">Options <span class="text-gray-400">(comma separated)</span></label>
                        @php
                            $optionsValue = old('options', $checklistItem->options ?? []);
                            if (is_array($optionsValue)) {
                                $optionsValue = implode(', ', $optionsValue);
                            }
                        @endphp
                        <input id="options" name="options" value="{{ $optionsValue }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Option 1, Option 2, Option 3" />
                        <p class="mt-2 text-xs text-gray-500">Only used when type is <strong>Select</strong>.</p>
                        @error('options')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $checklistItem->urutan) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" min="0" />
                        @error('urutan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_required" value="0">
                            <input type="checkbox" id="is_required" name="is_required" value="1" class="h-5 w-5 text-honda-red rounded focus:ring-honda-red" {{ old('is_required', $checklistItem->is_required) ? 'checked' : '' }}>
                            <label for="is_required" class="text-sm text-gray-700">Required item</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="h-5 w-5 text-honda-red rounded focus:ring-honda-red" {{ old('is_active', $checklistItem->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub-Items Section -->
            <div class="border-t pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-list-check mr-2"></i>
                        Sub-Items
                    </h3>
                    <button type="button" onclick="addSubItem()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors text-sm font-medium">
                        <i class="bi bi-plus-lg"></i>
                        Add Sub-Item
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-4">Add or edit sub-items (e.g., "dalam", "1 frame", "2 roda") that will be checked during maintenance.</p>

                <div id="subItemsContainer" class="space-y-3">
                    @forelse($checklistItem->subItems as $subItem)
                        <div class="subitem-row bg-gray-50 p-4 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Item Name *</label>
                                <input type="hidden" name="sub_items[{{ $subItem->id }}][id]" value="{{ $subItem->id }}" />
                                <input type="text" name="sub_items[{{ $subItem->id }}][judul]" value="{{ $subItem->judul }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., dalam" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <input type="text" name="sub_items[{{ $subItem->id }}][deskripsi]" value="{{ $subItem->deskripsi }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Optional" />
                            </div>
                            <div class="flex gap-2">
                                <input type="number" name="sub_items[{{ $subItem->id }}][urutan]" value="{{ $subItem->urutan }}" class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" min="0" />
                                <button type="button" onclick="removeSubItem(this)" class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="subitem-row bg-gray-50 p-4 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Item Name *</label>
                                <input type="text" name="sub_items[0][judul]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., dalam" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <input type="text" name="sub_items[0][deskripsi]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Optional" />
                            </div>
                            <div class="flex gap-2">
                                <input type="number" name="sub_items[0][urutan]" value="0" class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" min="0" />
                                <button type="button" onclick="removeSubItem(this)" class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
                @error('sub_items.*.judul')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3 border-t pt-6">
                <x-industrial-button variant="secondary" href="{{ route('checklist-items.index') }}" type="button">
                    Cancel
                </x-industrial-button>
                <x-industrial-button variant="primary" type="submit" icon="save">
                    Update Item
                </x-industrial-button>
            </div>
        </form>
    </div>

    <script>
        // Category Search Functionality
        const categoriesData = @json($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray());
        const searchInput = document.getElementById('categorySearch');
        const dropdown = document.getElementById('categoryDropdown');
        const categoryList = document.getElementById('categoryList');
        const selectedContainer = document.getElementById('selectedCategories');

        searchInput.addEventListener('focus', showCategories);
        searchInput.addEventListener('input', filterCategories);
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[id|="categorySearch"], [id|="categoryDropdown"], [id|="selectedCategories"]')) {
                dropdown.classList.add('hidden');
            }
        });

        function showCategories() {
            dropdown.classList.remove('hidden');
            filterCategories();
        }

        function filterCategories() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedIds = Array.from(document.querySelectorAll('input[name="unit_category_ids[]"]')).map(el => el.value);
            
            let html = '';
            const filtered = categoriesData.filter(cat => 
                cat.name.toLowerCase().includes(searchTerm) && !selectedIds.includes(String(cat.id))
            );

            if (filtered.length === 0) {
                html = '<div class="p-3 text-center text-gray-500 text-sm">Tidak ada kategori tersedia</div>';
            } else {
                filtered.forEach(cat => {
                    html += `
                        <button type="button" onclick="addCategory(${cat.id}, '${escapeHtml(cat.name)}')" 
                                class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 border-b border-gray-100 last:border-b-0 text-sm text-gray-700 transition-colors">
                            <i class="bi bi-plus-circle text-indigo-500 mr-2"></i>${escapeHtml(cat.name)}
                        </button>
                    `;
                });
            }
            categoryList.innerHTML = html;
        }

        function addCategory(id, name) {
            const selectedIds = Array.from(document.querySelectorAll('input[name="unit_category_ids[]"]')).map(el => el.value);
            if (selectedIds.includes(String(id))) return;

            const emptyText = document.getElementById('emptyText');
            if (emptyText) emptyText.remove();

            const badge = document.createElement('div');
            badge.className = `selectedCategory-${id} flex items-center gap-2 bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium`;
            badge.innerHTML = `
                <span>${escapeHtml(name)}</span>
                <button type="button" class="text-indigo-600 hover:text-indigo-800 font-bold" onclick="removeCategory(${id})">×</button>
                <input type="hidden" name="unit_category_ids[]" value="${id}">
            `;
            selectedContainer.appendChild(badge);
            
            searchInput.value = '';
            filterCategories();
            searchInput.focus();
        }

        function removeCategory(id) {
            const badge = document.querySelector(`.selectedCategory-${id}`);
            if (badge) badge.remove();
            
            const selectedIds = Array.from(document.querySelectorAll('input[name="unit_category_ids[]"]')).map(el => el.value);
            if (selectedIds.length === 0) {
                const emptyText = document.createElement('p');
                emptyText.id = 'emptyText';
                emptyText.className = 'text-gray-400 text-sm';
                emptyText.textContent = 'Pilih kategori...';
                selectedContainer.appendChild(emptyText);
            }
            
            filterCategories();
        }

        function escapeHtml(str) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return str.replace(/[&<>"']/g, m => map[m]);
        }

        let subItemCounter = {{ $checklistItem->subItems->max('id') ?? 0 }};

        function addSubItem() {
            const container = document.getElementById('subItemsContainer');
            subItemCounter++;
            const newRow = document.createElement('div');
            newRow.className = 'subitem-row bg-gray-50 p-4 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4 items-end';
            newRow.innerHTML = `
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Item Name *</label>
                    <input type="text" name="sub_items[new_${subItemCounter}][judul]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., dalam" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="sub_items[new_${subItemCounter}][deskripsi]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Optional" />
                </div>
                <div class="flex gap-2">
                    <input type="number" name="sub_items[new_${subItemCounter}][urutan]" value="${subItemCounter}" class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" min="0" />
                    <button type="button" onclick="removeSubItem(this)" class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        function removeSubItem(btn) {
            btn.closest('.subitem-row').remove();
        }
    </script>
</x-industrial-layout>
