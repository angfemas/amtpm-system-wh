<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fallback CSS for production -->
        @if(!app()->environment('local'))
            <style>
                /* Minimal styles as fallback */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; }
                .min-h-screen { min-height: 100vh; }
                .bg-gray-100 { background-color: #f3f4f6; }
                .bg-white { background-color: white; }
                .bg-gray-800 { background-color: #1f2937; }
                .bg-gray-900 { background-color: #111827; }
                .text-white { color: white; }
                .text-gray-300 { color: #d1d5db; }
                .text-gray-900 { color: #111827; }
                .text-gray-700 { color: #374151; }
                .p-4 { padding: 1rem; }
                .p-6 { padding: 1.5rem; }
                .px-4 { padding-left: 1rem; padding-right: 1rem; }
                .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
                .mx-auto { margin-left: auto; margin-right: auto; }
                .max-w-7xl { max-width: 80rem; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .items-center { align-items: center; }
                .justify-between { justify-content: space-between; }
                .space-y-1 > * + * { margin-top: 0.25rem; }
                .space-y-4 > * + * { margin-top: 1rem; }
                .space-y-6 > * + * { margin-top: 1.5rem; }
                .space-x-4 > * + * { margin-left: 1rem; }
                .w-64 { width: 16rem; }
                .h-16 { height: 4rem; }
                .h-screen { height: 100vh; }
                .min-h-screen { min-height: 100vh; }
                .fixed { position: fixed; }
                .top-0 { top: 0; }
                .left-0 { left: 0; }
                .right-0 { right: 0; }
                .border-b { border-bottom-width: 1px; }
                .border-gray-200 { border-color: #e5e7eb; }
                .border-r { border-right-width: 1px; }
                .shadow { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); }
                .rounded { border-radius: 0.25rem; }
                .rounded-lg { border-radius: 0.5rem; }
                .hidden { display: none; }
                .block { display: block; }
                .grid { display: grid; }
                .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
                .gap-6 { gap: 1.5rem; }
                .text-sm { font-size: 0.875rem; }
                .text-lg { font-size: 1.125rem; }
                .text-xl { font-size: 1.25rem; }
                .text-2xl { font-size: 1.5rem; }
                .font-semibold { font-weight: 600; }
                .font-bold { font-weight: 700; }
                .hover\:bg-gray-700:hover { background-color: #374151; }
                .hover\:bg-gray-900:hover { background-color: #111827; }
                .transition { transition-property: all; }
                .duration-150 { transition-duration: 150ms; }
                .ease-in-out { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
