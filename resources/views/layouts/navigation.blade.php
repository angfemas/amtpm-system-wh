<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @canany(['homepage.view', 'homepage.carousel.manage', 'homepage.slider.manage', 'homepage.flow.manage', 'homepage.sections.manage'])
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition">
                                <i class="bi bi-images me-2"></i>
                                Homepage
                                <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @can('homepage.view')
                            <x-dropdown-link :href="route('admin.homepage.index')">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </x-dropdown-link>
                            @endcan

                            @can('homepage.carousel.manage')
                            <x-dropdown-link :href="route('admin.homepage.carousel.index')">
                                <i class="bi bi-sliders me-2"></i>Carousel
                            </x-dropdown-link>
                            @endcan

                            @can('homepage.slider.manage')
                            <x-dropdown-link :href="route('admin.homepage.slider-rotate.index')">
                                <i class="bi bi-arrow-repeat me-2"></i>4 Pillars
                            </x-dropdown-link>
                            @endcan

                            @can('homepage.flow.manage')
                            <x-dropdown-link :href="route('admin.homepage.flow-process.index')">
                                <i class="bi bi-diagram-3 me-2"></i>Flow Process
                            </x-dropdown-link>
                            @endcan

                            @can('homepage.sections.manage')
                            <x-dropdown-link :href="route('admin.homepage.sections.index')">
                                <i class="bi bi-layout-text me-2"></i>Sections
                            </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                    @endcanany

                    @can('system.view')
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition">
                                <i class="bi bi-gear me-2"></i>
                                System Admin
                                <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.system.dashboard')">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.users.index')">
                                <i class="bi bi-people me-2"></i>Users
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.roles.index')">
                                <i class="bi bi-shield me-2"></i>Roles
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.permissions.index')">
                                <i class="bi bi-lock me-2"></i>Permissions
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.sections.index')">
                                <i class="bi bi-grid me-2"></i>Sections
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.activity-log')">
                                <i class="bi bi-clock-history me-2"></i>Activity Log
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.system.settings.index')">
                                <i class="bi bi-sliders me-2"></i>Settings
                            </x-dropdown-link>

                            @can('system.logo.manage')
                            <x-dropdown-link :href="route('admin.system.logo.index')">
                                <i class="bi bi-image me-2"></i>Logo
                            </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @canany(['homepage.view', 'homepage.carousel.manage', 'homepage.slider.manage', 'homepage.flow.manage', 'homepage.sections.manage'])
            <div class="px-4 pt-4 pb-2 border-t border-gray-200">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Homepage</div>
            </div>

            @can('homepage.view')
            <x-responsive-nav-link :href="route('admin.homepage.index')">
                Dashboard
            </x-responsive-nav-link>
            @endcan

            @can('homepage.carousel.manage')
            <x-responsive-nav-link :href="route('admin.homepage.carousel.index')">
                Carousel
            </x-responsive-nav-link>
            @endcan

            @can('homepage.slider.manage')
            <x-responsive-nav-link :href="route('admin.homepage.slider-rotate.index')">
                4 Pillars
            </x-responsive-nav-link>
            @endcan

            @can('homepage.flow.manage')
            <x-responsive-nav-link :href="route('admin.homepage.flow-process.index')">
                Flow Process
            </x-responsive-nav-link>
            @endcan

            @can('homepage.sections.manage')
            <x-responsive-nav-link :href="route('admin.homepage.sections.index')">
                Sections
            </x-responsive-nav-link>
            @endcan
            @endcanany

            @can('system.view')
            <div class="px-4 pt-4 pb-2 border-t border-gray-200">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System Admin</div>
            </div>

            @can('system.view')
            <x-responsive-nav-link :href="route('admin.system.dashboard')">
                Dashboard
            </x-responsive-nav-link>
            @endcan

            @can('system.users.manage')
            <x-responsive-nav-link :href="route('admin.system.users.index')">
                Users
            </x-responsive-nav-link>
            @endcan

            @can('system.roles.manage')
            <x-responsive-nav-link :href="route('admin.system.roles.index')">
                Roles
            </x-responsive-nav-link>
            @endcan

            @can('system.permissions.manage')
            <x-responsive-nav-link :href="route('admin.system.permissions.index')">
                Permissions
            </x-responsive-nav-link>
            @endcan

            @can('system.sections.manage')
            <x-responsive-nav-link :href="route('admin.system.sections.index')">
                Sections
            </x-responsive-nav-link>
            @endcan

            @can('system.activity.view')
            <x-responsive-nav-link :href="route('admin.system.activity-log')">
                Activity Log
            </x-responsive-nav-link>
            @endcan

            @can('system.settings.manage')
            <x-responsive-nav-link :href="route('admin.system.settings.index')">
                Settings
            </x-responsive-nav-link>
            @endcan

            @can('system.logo.manage')
            <x-responsive-nav-link :href="route('admin.system.logo.index')">
                Logo
            </x-responsive-nav-link>
            @endcan
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
