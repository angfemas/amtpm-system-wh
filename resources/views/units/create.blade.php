<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-plus-circle mr-3 text-blue-600"></i>
                    Create Unit
                </h1>
                <p class="mt-1 text-sm text-gray-500">Add new unit to the warehouse system</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('units.index') }}" icon="arrow-left">
                    Back to Units
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Create Unit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nomor_urut" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Number (No)
                    </label>
                    <div class="relative">
                        <input type="number"
                               id="nomor_urut"
                               name="nomor_urut"
                               min="1"
                               value="{{ old('nomor_urut', $nextNomorUrut) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent font-mono"
                               placeholder="{{ $nextNomorUrut }}">
                        <i class="bi bi-123 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Otomatis terisi nomor terakhir + 1 ({{ $nextNomorUrut }}). Bisa diubah manual atau dikosongkan untuk otomatis.</p>
                    @error('nomor_urut')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Code <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="kode_unit" 
                               name="kode_unit" 
                               required
                               value="{{ old('kode_unit') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent font-mono"
                               placeholder="HT-0001">
                        <i class="bi bi-hash absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('kode_unit')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_unit" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="nama_unit" 
                               name="nama_unit" 
                               required
                               value="{{ old('nama_unit') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Excavator CAT 320">
                        <i class="bi bi-truck absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('nama_unit')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="unit_category_id" 
                                name="unit_category_id" 
                                required
                                class="w-full appearance-none px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('unit_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('unit_category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="warehouse_area_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Warehouse Area <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="warehouse_area_id" 
                                name="warehouse_area_id" 
                                required
                                class="w-full appearance-none px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('warehouse_area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('warehouse_area_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                        Maintenance Type <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="jenis_maintenance" 
                                name="jenis_maintenance" 
                                class="w-full appearance-none px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="preventive" {{ old('jenis_maintenance') == 'preventive' ? 'selected' : '' }}>Preventive</option>
                            <option value="corrective" {{ old('jenis_maintenance') == 'corrective' ? 'selected' : '' }}>Corrective</option>
                            <option value="predictive" {{ old('jenis_maintenance') == 'predictive' ? 'selected' : '' }}>Predictive</option>
                        </select>
                        <i class="bi bi-gear absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('jenis_maintenance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_maintenance_terakhir" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Maintenance Date
                    </label>
                    <div class="relative">
                        <input type="date" 
                               id="tanggal_maintenance_terakhir" 
                               name="tanggal_maintenance_terakhir" 
                               value="{{ old('tanggal_maintenance_terakhir') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <i class="bi bi-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('tanggal_maintenance_terakhir')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="interval_hari" class="block text-sm font-medium text-gray-700 mb-2">
                        Maintenance Interval (Days) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="interval_hari" 
                               name="interval_hari" 
                               min="1"
                               required
                               value="{{ old('interval_hari', 30) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <i class="bi bi-calendar-week absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('interval_hari')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kilometer" class="block text-sm font-medium text-gray-700 mb-2">
                        Kilometer
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="kilometer" 
                               name="kilometer" 
                               step="0.01"
                               min="0"
                               value="{{ old('kilometer') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <i class="bi bi-speedometer2 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('kilometer')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hour_meter" class="block text-sm font-medium text-gray-700 mb-2">
                        Hour Meter
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="hour_meter" 
                               name="hour_meter" 
                               step="0.01"
                               min="0"
                               value="{{ old('hour_meter') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <i class="bi bi-clock-history absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('hour_meter')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Description/Notes
                </label>
                <textarea id="keterangan" 
                          name="keterangan" 
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Enter unit description or notes">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="foto_unit" class="block text-sm font-medium text-gray-700 mb-2">
                    Unit Photo
                </label>
                <input type="file"
                       id="foto_unit"
                       name="foto_unit"
                       accept="image/*"
                       class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200"
                >
                @error('foto_unit')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <x-industrial-button variant="secondary" type="button" href="{{ route('units.index') }}">
                    Cancel
                </x-industrial-button>
                <x-industrial-button variant="primary" type="submit" icon="save">
                    Create Unit
                </x-industrial-button>
            </div>
        </form>
    </div>
</x-industrial-layout>
