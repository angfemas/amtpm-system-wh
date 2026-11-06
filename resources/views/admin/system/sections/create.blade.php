@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Add New Section</h2>
                    <p class="text-gray-600 text-sm mt-1">Instructions for adding warehouse sections</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <svg class="h-6 w-6 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-900">How to Add a New Section</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>To add a new warehouse section, follow these steps:</p>
                                <ol class="list-decimal list-inside mt-2 space-y-1">
                                    <li>Open the file <code class="bg-blue-100 px-1 rounded">config/sections.php</code></li>
                                    <li>Add the new section to the <code class="bg-blue-100 px-1 rounded">'sections'</code> array</li>
                                    <li>Format: <code class="bg-blue-100 px-1 rounded">'SECTION_KEY' => 'Section Name'</code></li>
                                    <li>Save the file and clear config cache: <code class="bg-blue-100 px-1 rounded">php artisan config:clear</code></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Example</h3>
                    <pre class="bg-gray-800 text-green-400 p-4 rounded-lg overflow-x-auto"><code>&lt;?php

return [
    'sections' => [
        'PKWH1' => 'PKWH1',
        'PKWH2' => 'PKWH2',
        // Add your new section here:
        'PKWH7' => 'PKWH7',
    ],
    'default_section' => env('DEFAULT_SECTION', null),
];</code></pre>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.system.sections.index') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Back to Sections
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
