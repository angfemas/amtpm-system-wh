@props([
    'overdueUnits' => [],
    'show' => false
])

<div x-data="{ show: {{ $show ? 'true' : 'false' }} }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        
        <!-- Header -->
        <div class="bg-red-600 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="bi bi-exclamation-octagon-fill text-2xl"></i>
                    <div>
                        <h3 class="text-lg font-bold">Overdue Maintenance Alert</h3>
                        <p class="text-sm opacity-90">{{ count($overdueUnits) }} units require immediate attention</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <div class="space-y-3">
                @foreach($overdueUnits as $unit)
                    <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $unit['name'] }}</h4>
                                <p class="text-sm text-red-600">{{ $unit['overdue'] }} days overdue</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $unit['lastMaintenance'] }}</span>
                            <button class="btn-primary text-sm">
                                Schedule Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <button class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="bi bi-bell-slash mr-2"></i>
                    Dismiss all alerts
                </button>
                <div class="space-x-3">
                    <button @click="show = false" class="btn-secondary">
                        Remind Later
                    </button>
                    <button class="btn-primary">
                        View All Overdue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
