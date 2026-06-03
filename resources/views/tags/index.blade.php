<x-industrial-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tag Management</h1>
                <p class="mt-1 text-sm text-gray-500">Red Tag & White Tag System</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" icon="tag">
                    Create White Tag
                </x-industrial-button>
                <x-industrial-button variant="danger" icon="x-octagon">
                    Create Red Tag
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-900">Active Red Tags</p>
                    <p class="text-2xl font-bold text-red-900">7</p>
                    <p class="text-xs text-red-700">3 critical</p>
                </div>
                <div class="w-12 h-12 bg-red-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-x-octagon-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700">Active White Tags</p>
                    <p class="text-2xl font-bold text-gray-700">12</p>
                    <p class="text-xs text-gray-500">5 this week</p>
                </div>
                <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-tag-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-900">Pending Review</p>
                    <p class="text-2xl font-bold text-yellow-900">4</p>
                    <p class="text-xs text-yellow-700">Awaiting approval</p>
                </div>
                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-900">Resolved Today</p>
                    <p class="text-2xl font-bold text-green-900">3</p>
                    <p class="text-xs text-green-700">2 red, 1 white</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle-fill text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="industrial-card mb-6">
        <div class="flex items-center space-x-1 border-b border-gray-200">
            <button class="px-4 py-3 text-sm font-medium text-honda-red border-b-2 border-honda-red">
                All Tags (19)
            </button>
            <button class="px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                Red Tags (7)
            </button>
            <button class="px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                White Tags (12)
            </button>
            <button class="px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                Critical (3)
            </button>
            <button class="px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                Resolved (45)
            </button>
        </div>
    </div>

    <!-- Tags Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Red Tag Cards -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="bi bi-x-octagon-fill text-red-900 mr-2"></i>
                Red Tags
            </h3>
            
            <!-- Critical Red Tag -->
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-red-900 text-white text-xs px-2 py-1 rounded-bl-lg">
                    CRITICAL
                </div>
                
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-red-900">Hydraulic System Failure</h4>
                        <p class="text-sm text-red-700">Unit HT-0345 • Reach Truck</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-red-600">2 hours ago</p>
                        <x-status-badge status="red-tag" />
                    </div>
                </div>
                
                <p class="text-sm text-red-800 mb-4">
                    Major hydraulic leak detected. Unit unsafe for operation. Immediate repair required.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-red-600">Reported by</p>
                        <p class="text-sm font-medium text-red-900">Mike Johnson</p>
                    </div>
                    <div>
                        <p class="text-xs text-red-600">Location</p>
                        <p class="text-sm font-medium text-red-900">Warehouse B</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="btn-primary text-sm">
                        <i class="bi bi-tools mr-1"></i> Start Repair
                    </button>
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-eye mr-1"></i> Details
                    </button>
                </div>
            </div>

            <!-- Regular Red Tag -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-red-900">Brake System Malfunction</h4>
                        <p class="text-sm text-red-700">Unit HT-0678 • Pallet Jack</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-red-600">5 hours ago</p>
                        <x-status-badge status="red-tag" />
                    </div>
                </div>
                
                <p class="text-sm text-red-800 mb-4">
                    Front brakes not responding properly. Safety hazard identified.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-red-600">Reported by</p>
                        <p class="text-sm font-medium text-red-900">Sarah Davis</p>
                    </div>
                    <div>
                        <p class="text-xs text-red-600">Location</p>
                        <p class="text-sm font-medium text-red-900">Workshop</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="btn-primary text-sm">
                        <i class="bi bi-tools mr-1"></i> Start Repair
                    </button>
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-eye mr-1"></i> Details
                    </button>
                </div>
            </div>
        </div>

        <!-- White Tag Cards -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="bi bi-tag-fill text-gray-600 mr-2"></i>
                White Tags
            </h3>
            
            <!-- White Tag -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-700">Minor Scratch on Side Panel</h4>
                        <p class="text-sm text-gray-600">Unit HT-0234 • Forklift Electric</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">3 days ago</p>
                        <x-status-badge status="white-tag" />
                    </div>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">
                    Cosmetic damage on right side panel. Does not affect operation but should be repaired.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Reported by</p>
                        <p class="text-sm font-medium text-gray-700">John Smith</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Location</p>
                        <p class="text-sm font-medium text-gray-700">Warehouse A</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-brush mr-1"></i> Schedule Repair
                    </button>
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-eye mr-1"></i> Details
                    </button>
                    <button class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-circle mr-1"></i> Resolve
                    </button>
                </div>
            </div>

            <!-- White Tag -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-700">Loose Side Mirror</h4>
                        <p class="text-sm text-gray-600">Unit HT-0789 • Order Picker</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">1 week ago</p>
                        <x-status-badge status="white-tag" />
                    </div>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">
                    Driver's side mirror slightly loose. Needs tightening before next use.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Reported by</p>
                        <p class="text-sm font-medium text-gray-700">Tom Wilson</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Location</p>
                        <p class="text-sm font-medium text-gray-700">Warehouse A</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-wrench mr-1"></i> Quick Fix
                    </button>
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-eye mr-1"></i> Details
                    </button>
                    <button class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-circle mr-1"></i> Resolve
                    </button>
                </div>
            </div>

            <!-- White Tag -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-700">Seat Cushion Wear</h4>
                        <p class="text-sm text-gray-600">Unit HT-0901 • Stackers</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">2 weeks ago</p>
                        <x-status-badge status="white-tag" />
                    </div>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">
                    Operator seat cushion showing wear. Replacement recommended for comfort.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Reported by</p>
                        <p class="text-sm font-medium text-gray-700">Lisa Chen</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Location</p>
                        <p class="text-sm font-medium text-gray-700">Warehouse C</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-cart-plus mr-1"></i> Order Part
                    </button>
                    <button class="btn-secondary text-sm">
                        <i class="bi bi-eye mr-1"></i> Details
                    </button>
                    <button class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-circle mr-1"></i> Resolve
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-industrial-layout>
