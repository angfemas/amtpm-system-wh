@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Activity Log</h2>
                    <p class="text-gray-600 text-sm mt-1">View system activity and audit logs</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <svg class="h-6 w-6 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-900">Activity Log Package Required</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>To enable full activity logging, install the <code class="bg-blue-100 px-1 rounded">spatie/laravel-activitylog</code> package:</p>
                                <pre class="bg-blue-100 p-2 rounded mt-2 text-xs overflow-x-auto"><code>composer require spatie/laravel-activitylog</code></pre>
                                <p class="mt-2">After installation, publish the configuration and run migrations:</p>
                                <pre class="bg-blue-100 p-2 rounded mt-2 text-xs overflow-x-auto"><code>php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Logging Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-3 w-3 bg-yellow-400 rounded-full"></div>
                            <span class="ml-3 text-sm text-gray-700">Basic logging via Laravel Log facade is enabled</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-3 w-3 bg-gray-400 rounded-full"></div>
                            <span class="ml-3 text-sm text-gray-700">Detailed activity log requires package installation</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic System Logs</h3>
                    <div class="bg-gray-800 text-green-400 p-4 rounded-lg overflow-x-auto max-h-96">
                        <pre class="text-xs">@if(file_exists(storage_path('logs/laravel.log')))
                            {{ file_get_contents(storage_path('logs/laravel.log')) }}
                        @else
                            No log file found.
                        @endif</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
