<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Warehouse AMTPM System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak]{display:none !important;}</style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ 
    sidebarOpen: false,
    showNotifications: false,
    unreadCount: 0 
}">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed lg:static lg:translate-x-0 z-50 w-64 h-full bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out shadow-lg">
            
            <!-- Logo Section -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center shadow-md">
                        <i class="bi bi-gear-fill text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-gray-800">AMTPM</span>
                        <p class="text-xs text-gray-500">Warehouse System</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-x-lg text-gray-600"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('dashboard') 
                              ? 'bg-red-50 text-red-700 border-l-4 border-red-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-speedometer2 text-lg mr-3 {{ request()->routeIs('dashboard') ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="ml-auto w-2 h-2 bg-red-600 rounded-full"></span>
                    @endif
                </a>

                <!-- Master Data Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                </div>
                
                @if(Route::has('units.index'))
                <a href="{{ route('units.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('units.*') 
                              ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-truck text-lg mr-3 {{ request()->routeIs('units.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Units</span>
                </a>
                @endif
                
                @if(Route::has('unit-categories.index'))
                <a href="{{ route('unit-categories.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('unit-categories.*') 
                              ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-tags text-lg mr-3 {{ request()->routeIs('unit-categories.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Categories</span>
                </a>
                @endif

                @if(Route::has('checklist-items.index'))
                <a href="{{ route('checklist-items.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('checklist-items.*') 
                              ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-card-checklist text-lg mr-3 {{ request()->routeIs('checklist-items.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Checklist Items</span>
                </a>
                @endif

                @if(Route::has('warehouse-areas.index'))
                <a href="{{ route('warehouse-areas.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('warehouse-areas.*') 
                              ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-building text-lg mr-3 {{ request()->routeIs('warehouse-areas.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Warehouse Areas</span>
                </a>
                @endif

                <!-- Operations Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Operations</p>
                </div>

                @if(Route::has('maintenance-logs.index'))
                <a href="{{ route('maintenance-logs.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('maintenance-logs.*') 
                              ? 'bg-green-50 text-green-700 border-l-4 border-green-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-wrench text-lg mr-3 {{ request()->routeIs('maintenance-logs.*') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Maintenance</span>
                </a>
                @endif

                @if(Route::has('red-white-tags.index'))
                <a href="{{ route('red-white-tags.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('red-white-tags.*') 
                              ? 'bg-orange-50 text-orange-700 border-l-4 border-orange-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-exclamation-triangle-fill text-lg mr-3 {{ request()->routeIs('red-white-tags.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>Red/White Tags</span>
                </a>
                @endif

                <!-- Tools Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tools</p>
                </div>

                @if(Route::has('qr-codes.index'))
                <a href="{{ route('qr-codes.index') }}" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('qr-codes.*') 
                              ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600' 
                              : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="bi bi-qr-code text-lg mr-3 {{ request()->routeIs('qr-codes.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span>QR Codes</span>
                </a>
                @endif

                <!-- Reports Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</p>
                </div>

                <a href="#" 
                   class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                    <i class="bi bi-file-text text-lg mr-3 text-gray-400 group-hover:text-gray-600"></i>
                    <span>Reports</span>
                    <span class="ml-auto">
                        <i class="bi bi-chevron-right text-xs text-gray-400"></i>
                    </span>
                </a>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center space-x-3 p-3 rounded-lg bg-white shadow-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-sm">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <button class="p-1 rounded hover:bg-gray-100 transition-colors">
                        <i class="bi bi-chevron-up text-gray-400"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-white border-b border-gray-200 shadow-sm z-10">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-list text-xl text-gray-600"></i>
                    </button>

                    <!-- Search Bar -->
                    <div class="hidden md:flex flex-1 max-w-lg mx-8">
                        <div class="relative w-full">
                            <input type="text" placeholder="Search units, maintenance, reports..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-3">
                        
                        <!-- Notifications -->
                        <div class="relative" x-data="{ notificationOpen: false }">
                            <button @click="notificationOpen = !notificationOpen" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                <i class="bi bi-bell text-xl"></i>
                                <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-semibold" x-text="unreadCount"></span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div x-show="notificationOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 @click.away="notificationOpen = false"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">
                                        <i class="bi bi-bell-slash text-2xl mb-2"></i>
                                        <p class="text-sm">No new notifications</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                            <i class="bi bi-gear text-xl"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                    <i class="bi bi-person-fill text-white text-sm"></i>
                                </div>
                                <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <!-- User Menu Dropdown -->
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 @click.away="userMenuOpen = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-person mr-3 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-gear mr-3 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <i class="bi bi-box-arrow-right mr-3 text-gray-400"></i>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <!-- Page Heading -->
                @isset($header)
                    <div class="bg-white border-b border-gray-200">
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Page Content -->
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</body>
</html>

<script>
    function showNotification(type, message) {
        const container = document.getElementById('notification-container');
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `transform transition-all duration-300 translate-x-full max-w-sm w-full ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' :
            'bg-blue-500'
        } text-white rounded-lg shadow-lg p-4 mb-2`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="bi ${
                    type === 'success' ? 'bi-check-circle-fill' :
                    type === 'error' ? 'bi-x-circle-fill' :
                    type === 'warning' ? 'bi-exclamation-triangle-fill' :
                    'bi-info-circle-fill'
                } mr-3 text-lg"></i>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:opacity-80">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        
        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
    
    // Global notification function
    window.showNotification = showNotification;
</script>
