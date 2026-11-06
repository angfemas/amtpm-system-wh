@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <a href="{{ route('admin.homepage.slider-rotate.index') }}"
                       class="text-gray-600 hover:text-gray-900">
                        <i class="bi bi-arrow-left mr-2"></i>Back to 4 Pillars
                    </a>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-6">Create 4 Pillars Item</h2>

                <form action="{{ route('admin.homepage.slider-rotate.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter title"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea name="description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Enter description"
                                      required>{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <input type="text"
                                   name="subtitle"
                                   value="{{ old('subtitle') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter subtitle (optional)">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            <input type="file"
                                   name="icon_path"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   id="iconInput">
                            <p class="mt-1 text-sm text-gray-500">Accepted formats: JPEG, PNG, JPG, GIF, SVG, WebP (Max: 10MB)</p>
                            <div id="iconPreview" class="mt-3 hidden">
                                <img id="previewIcon" src="" alt="Preview" class="h-16 w-16 object-contain rounded">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position (1-4) <span class="text-red-500">*</span></label>
                                <select name="position"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <option value="1" {{ old('position') == 1 ? 'selected' : '' }}>Position 1</option>
                                    <option value="2" {{ old('position') == 2 ? 'selected' : '' }}>Position 2</option>
                                    <option value="3" {{ old('position') == 3 ? 'selected' : '' }}>Position 3</option>
                                    <option value="4" {{ old('position') == 4 ? 'selected' : '' }}>Position 4</option>
                                </select>
                            </div>

                            <div class="flex items-center pt-6">
                                <input type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('admin.homepage.slider-rotate.index') }}"
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Create Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('iconInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('iconPreview');
        const previewIcon = document.getElementById('previewIcon');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewIcon.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endsection
