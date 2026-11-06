@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
                    <p class="text-gray-600 text-sm mt-1">Configure system-wide settings</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.system.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- General Settings -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                    <input type="text" name="site_name" id="site_name"
                                           value="{{ \App\Models\AppSetting::getValue('site_name', config('app.name')) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                                    <input type="text" name="site_description" id="site_description"
                                           value="{{ \App\Models\AppSetting::getValue('site_description', '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- User Default Settings -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Default Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="default_role" class="block text-sm font-medium text-gray-700 mb-2">Default Role for New Users</label>
                                    <select name="default_role" id="default_role"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="admin" {{ \App\Models\AppSetting::getValue('default_role', config('amtpm.default_role')) === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="leader" {{ \App\Models\AppSetting::getValue('default_role', config('amtpm.default_role')) === 'leader' ? 'selected' : '' }}>Leader</option>
                                        <option value="operator" {{ \App\Models\AppSetting::getValue('default_role', config('amtpm.default_role')) === 'operator' ? 'selected' : '' }}>Operator</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="default_section" class="block text-sm font-medium text-gray-700 mb-2">Default Section for New Users</label>
                                    <select name="default_section" id="default_section"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">None</option>
                                        @foreach(config('sections.sections') as $key => $name)
                                            <option value="{{ $key }}" {{ \App\Models\AppSetting::getValue('default_section', config('sections.default_section')) === $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Image Compression Settings -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Image Compression Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">Configure automatic image compression for uploaded images to optimize storage and performance.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="max_width" class="block text-sm font-medium text-gray-700 mb-2">Max Width (px)</label>
                                    <input type="number" name="max_width" id="max_width"
                                           value="{{ config('image-compression.max_width', 1920) }}"
                                           min="800" max="4096"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Images wider than this will be resized</p>
                                </div>
                                <div>
                                    <label for="max_height" class="block text-sm font-medium text-gray-700 mb-2">Max Height (px)</label>
                                    <input type="number" name="max_height" id="max_height"
                                           value="{{ config('image-compression.max_height', 1080) }}"
                                           min="600" max="4096"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Images taller than this will be resized</p>
                                </div>
                                <div>
                                    <label for="quality" class="block text-sm font-medium text-gray-700 mb-2">JPEG/WebP Quality ({{ config('image-compression.quality', 80) }}%)</label>
                                    <input type="range" name="quality" id="quality"
                                           value="{{ config('image-compression.quality', 80) }}"
                                           min="10" max="100"
                                           class="w-full"
                                           oninput="document.getElementById('qualityValue').textContent = this.value + '%'">
                                    <p class="text-xs text-gray-500 mt-1">Higher = better quality, larger file size</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quality Preview</label>
                                    <div id="qualityValue" class="text-lg font-bold text-blue-600">{{ config('image-compression.quality', 80) }}%</div>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="convert_to_webp" id="convert_to_webp"
                                           {{ config('image-compression.convert_to_webp', false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="convert_to_webp" class="ml-2 text-sm text-gray-700">Convert to WebP</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="progressive_jpeg" id="progressive_jpeg"
                                           {{ config('image-compression.progressive_jpeg', true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="progressive_jpeg" class="ml-2 text-sm text-gray-700">Progressive JPEG</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="optimize_png" id="optimize_png"
                                           {{ config('image-compression.optimize_png', true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="optimize_png" class="ml-2 text-sm text-gray-700">Optimize PNG</label>
                                </div>
                            </div>
                        </div>

                        <!-- Environment Information -->
                        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Environment Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">App Environment:</span>
                                    <span class="ml-2 text-gray-600">{{ config('app.env') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Debug Mode:</span>
                                    <span class="ml-2 {{ config('app.debug') ? 'text-green-600' : 'text-red-600' }}">
                                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">App URL:</span>
                                    <span class="ml-2 text-gray-600">{{ config('app.url') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Timezone:</span>
                                    <span class="ml-2 text-gray-600">{{ config('app.timezone') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Locale:</span>
                                    <span class="ml-2 text-gray-600">{{ config('app.locale') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Laravel Version:</span>
                                    <span class="ml-2 text-gray-600">{{ app()->version() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
