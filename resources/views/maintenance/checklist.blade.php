<x-industrial-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button class="p-2 text-gray-500 hover:text-gray-700">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Maintenance Checklist</h1>
                    <p class="mt-1 text-sm text-gray-500">Unit HT-0234 - Forklift Electric</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" icon="save">
                    Save Draft
                </x-industrial-button>
                <x-industrial-button variant="primary" icon="check-circle">
                    Submit Checklist
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Checklist Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Progress Bar -->
            <x-industrial-card>
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Overall Progress</span>
                        <span class="text-sm text-gray-500">65% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-honda-red to-repsol-orange h-3 rounded-full transition-all duration-300" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-green-600">12</div>
                        <div class="text-xs text-gray-500">Completed</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">3</div>
                        <div class="text-xs text-gray-500">In Progress</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-yellow-600">5</div>
                        <div class="text-xs text-gray-500">Pending</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-400">20</div>
                        <div class="text-xs text-gray-500">Total Items</div>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Safety Checklist -->
            <x-industrial-card title="Safety Inspection">
                <div class="space-y-4">
                    <!-- Checklist Item -->
                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="safety1" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red" checked>
                            <div class="flex-1">
                                <label for="safety1" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Emergency stop functionality tested
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Verify all emergency stop buttons are functional</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="safety2" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red" checked>
                            <div class="flex-1">
                                <label for="safety2" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Seat belt and operator restraint system
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Check seat belt condition and locking mechanism</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="safety3" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red">
                            <div class="flex-1">
                                <label for="safety3" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Horn and warning devices operational
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Test horn, backup alarm, and warning lights</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="safety4" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red">
                            <div class="flex-1">
                                <label for="safety4" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Fire extinguisher inspection
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Check fire extinguisher pressure and expiry date</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Mechanical Checklist -->
            <x-industrial-card title="Mechanical Inspection">
                <div class="space-y-4">
                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="mech1" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red" checked>
                            <div class="flex-1">
                                <label for="mech1" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Fluid levels (oil, hydraulic, coolant)
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Check and top up all fluid levels as needed</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="mech2" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red">
                            <div class="flex-1">
                                <label for="mech2" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Fork and mast inspection
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Check for cracks, wear, and proper operation</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checklist-item">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <input type="checkbox" id="mech3" class="mt-1 w-5 h-5 text-honda-red rounded focus:ring-honda-red">
                            <div class="flex-1">
                                <label for="mech3" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Chain tension and lubrication
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Check lift chain tension and lubricate</p>
                                <div class="mt-2">
                                    <textarea placeholder="Add notes..." 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Image Upload Section -->
            <x-industrial-card title="Photo Documentation">
                <div class="space-y-4">
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-honda-red transition-colors cursor-pointer"
                         onclick="document.getElementById('imageUpload').click()">
                        <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        <input type="file" id="imageUpload" class="hidden" multiple accept="image/*" onchange="previewImages(event)">
                    </div>

                    <!-- Image Preview Grid -->
                    <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Sample uploaded images -->
                        <div class="relative group">
                            <img src="https://via.placeholder.com/200x150/E5E7EB/6B7280?text=Before" 
                                 alt="Upload" 
                                 class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <button class="text-white p-1" onclick="removeImage(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="text-white p-1 ml-2" onclick="viewImage(this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">Before maintenance</p>
                        </div>

                        <div class="relative group">
                            <img src="https://via.placeholder.com/200x150/E5E7EB/6B7280?text=After" 
                                 alt="Upload" 
                                 class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <button class="text-white p-1" onclick="removeImage(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="text-white p-1 ml-2" onclick="viewImage(this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">After maintenance</p>
                        </div>
                    </div>
                </div>
            </x-industrial-card>
        </div>

        <!-- Right Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Unit Info -->
            <x-industrial-card title="Unit Information">
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="bi bi-truck text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">HT-0234</p>
                            <p class="text-xs text-gray-500">Forklift Electric</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-3 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Model</span>
                            <span class="font-medium">CAT EP16KT</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Location</span>
                            <span class="font-medium">Warehouse A</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Hours</span>
                            <span class="font-medium">1,234 hrs</span>
                        </div>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Maintenance Details -->
            <x-industrial-card title="Maintenance Details">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                            <option>Routine Service</option>
                            <option>Repair</option>
                            <option>Inspection</option>
                            <option>Emergency</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Technician</label>
                        <input type="text" 
                               value="John Smith" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" 
                               value="{{ date('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (hours)</label>
                        <input type="number" 
                               placeholder="2.5" 
                               step="0.5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>

                    @error('maintenance_type')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-medium text-red-800">Maintenance type required</p>
                                    <ul class="mt-1 text-sm text-red-700">
                                        @foreach ($errors->get('maintenance_type') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="history.back()" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-save mr-2"></i> Submit Checklist
                        </button>
                    </div>
                </form>
            </x-industrial-card>

            <!-- Parts Used -->
            <x-industrial-card title="Parts Used">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Oil Filter</p>
                            <p class="text-xs text-gray-500">Part #OF-1234</p>
                        </div>
                        <span class="text-sm font-medium">1</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Engine Oil</p>
                            <p class="text-xs text-gray-500">Part #EO-5678</p>
                        </div>
                        <span class="text-sm font-medium">5L</span>
                    </div>
                    
                    <button type="button" class="w-full py-2 border border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-honda-red hover:text-honda-red transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i> Add Part
                    </button>
                </div>
            </x-industrial-card>

            <!-- Summary Notes -->
            <x-industrial-card title="Summary Notes">
                <textarea placeholder="Enter overall maintenance summary and recommendations..." 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"
                          rows="6"></textarea>
            </x-industrial-card>
        </div>
    </div>

    <script>
        function previewImages(event) {
            const files = event.target.files;
            const preview = document.getElementById('imagePreview');
            
            for (let file of files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Upload" class="w-full h-24 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <button class="text-white p-1" onclick="removeImage(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="text-white p-1 ml-2" onclick="viewImage(this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-center">${file.name}</p>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        }
        
        function removeImage(button) {
            button.closest('.relative').remove();
        }
        
        function viewImage(button) {
            const img = button.closest('.relative').querySelector('img');
            window.open(img.src, '_blank');
        }
    </script>
</x-industrial-layout>
