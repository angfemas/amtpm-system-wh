@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Logo Management</h2>
                        <p class="text-gray-600 text-sm mt-1">Manage application logos</p>
                    </div>
                    <a href="{{ route('admin.system.logo.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-plus-circle mr-2"></i>Add Logo
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($logos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($logos as $logo)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        {{ $logo->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $logo->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $logo->logo_type }}</span>
                                </div>

                                @if($logo->image_path)
                                    <div class="mb-4 flex justify-center bg-gray-50 rounded-lg p-4">
                                        <img src="{{ $logo->image_url }}"
                                             alt="{{ $logo->alt_text ?? 'Logo' }}"
                                             class="max-h-32 object-contain">
                                    </div>
                                @else
                                    <div class="mb-4 flex justify-center bg-gray-50 rounded-lg p-4">
                                        <div class="text-gray-400 text-sm">No image</div>
                                    </div>
                                @endif

                                <div class="space-y-2 text-sm text-gray-600">
                                    @if($logo->alt_text)
                                        <div><strong>Alt Text:</strong> {{ $logo->alt_text }}</div>
                                    @endif
                                    @if($logo->width || $logo->height)
                                        <div><strong>Size:</strong> {{ $logo->width }}x{{ $logo->height }}px</div>
                                    @endif
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <a href="{{ route('admin.system.logo.edit', $logo) }}"
                                       class="px-3 py-1 bg-blue-50 text-blue-700 rounded text-sm hover:bg-blue-100 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.system.logo.destroy', $logo) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this logo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-50 text-red-700 rounded text-sm hover:bg-red-100 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="bi bi-images"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No logos found</h3>
                        <p class="text-gray-600 mb-4">Get started by adding your first logo.</p>
                        <a href="{{ route('admin.system.logo.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Add Logo
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
