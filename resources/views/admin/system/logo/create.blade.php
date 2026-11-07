@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Add New Logo</h2>
                    <p class="text-gray-600 text-sm mt-1">Upload and configure a new logo</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.system.logo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <!-- Logo Type -->
                        <div>
                            <label for="logo_type" class="block text-sm font-medium text-gray-700 mb-2">Logo Type</label>
                            <select name="logo_type" id="logo_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="default">Default Logo</option>
                                <option value="favicon">Favicon</option>
                                <option value="small">Small Logo</option>
                                <option value="large">Large Logo</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose the type of logo you're uploading</p>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="image_path" class="block text-sm font-medium text-gray-700 mb-2">Logo Image</label>
                            <input type="file" name="image_path" id="image_path"
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Supported formats: JPEG, PNG, JPG, GIF, WebP, SVG (Max 5MB)</p>
                        </div>

                        <!-- Alt Text -->
                        <div>
                            <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                            <input type="text" name="alt_text" id="alt_text"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Logo description for accessibility">
                            <p class="mt-1 text-sm text-gray-500">Optional: Alternative text for screen readers</p>
                        </div>

                        <!-- Dimensions -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700 mb-2">Width (px)</label>
                                <input type="number" name="width" id="width"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="200"
                                       min="10" max="2000">
                                <p class="mt-1 text-sm text-gray-500">Optional: Display width</p>
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height (px)</label>
                                <input type="number" name="height" id="height"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="200"
                                       min="10" max="2000">
                                <p class="mt-1 text-sm text-gray-500">Optional: Display height</p>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   checked>
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                            <p class="ml-4 text-sm text-gray-500">Enable this logo for display</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.system.logo.index') }}"
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Save Logo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
