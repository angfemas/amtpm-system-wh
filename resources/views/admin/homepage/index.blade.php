@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Homepage Management</h2>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Carousel Items Card -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Carousel Items</h3>
                                <p class="text-sm opacity-90">Manage homepage carousel</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-images"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold">{{ \App\Models\HomepageCarouselItem::count() }}</span>
                            <a href="{{ route('admin.homepage.carousel.index') }}"
                               class="px-3 py-1 bg-white text-red-600 rounded text-sm font-medium hover:bg-red-50 transition">
                                Manage
                            </a>
                        </div>
                    </div>

                    <!-- Slider Rotate Items Card -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">4 Pillars</h3>
                                <p class="text-sm opacity-90">Manage rotating sliders</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold">{{ \App\Models\HomepageSliderRotateItem::count() }}</span>
                            <a href="{{ route('admin.homepage.slider-rotate.index') }}"
                               class="px-3 py-1 bg-white text-blue-600 rounded text-sm font-medium hover:bg-blue-50 transition">
                                Manage
                            </a>
                        </div>
                    </div>

                    <!-- Flow Process Items Card -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Flow Process</h3>
                                <p class="text-sm opacity-90">Manage process steps</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold">{{ \App\Models\HomepageFlowProcessItem::count() }}</span>
                            <a href="{{ route('admin.homepage.flow-process.index') }}"
                               class="px-3 py-1 bg-white text-green-600 rounded text-sm font-medium hover:bg-green-50 transition">
                                Manage
                            </a>
                        </div>
                    </div>

                    <!-- Sections Card -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Sections</h3>
                                <p class="text-sm opacity-90">Manage page sections</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-layout-three-columns"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold">{{ \App\Models\HomepageSection::count() }}</span>
                            <a href="{{ route('admin.homepage.sections.index') }}"
                               class="px-3 py-1 bg-white text-purple-600 rounded text-sm font-medium hover:bg-purple-50 transition">
                                Manage
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.homepage.carousel.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Add Carousel
                        </a>
                        <a href="{{ route('admin.homepage.slider-rotate.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Add Slider
                        </a>
                        <a href="{{ route('admin.homepage.flow-process.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Add Process
                        </a>
                        <a href="{{ route('admin.homepage.sections.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Add Section
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
