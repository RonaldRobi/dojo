<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Droplets Dojo') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        sidebar: {
                            DEFAULT: '#283046',
                            600: '#1e2742',
                            700: '#161d31',
                        }
                    }
                },
            },
        }
    </script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    @auth
        <!-- Mobile Drawer Button - Simple Test -->
        <button onclick="alert('Mobile button works!')" class="lg:hidden" style="position: fixed; top: 5rem; left: 1rem; z-index: 9999; padding: 1rem; background: red; color: white; border-radius: 0.5rem; font-weight: bold;">
            MENU
        </button>
        
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                <!-- Top Header -->
                @include('layouts.navigation')
                
                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 p-3 sm:p-4 lg:p-6" style="padding-bottom: calc(5rem + env(safe-area-inset-bottom));">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @if (isset($errors) && $errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{ $slot }}
                </main>
            </div>
        </div>
    @endauth
    
    <!-- Mobile Bottom Navigation - Direct HTML Test -->
    @auth
        <div class="lg:hidden" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background: #ff0000; color: white; padding: 1rem; text-align: center; font-weight: bold;">
            BOTTOM NAV TEST - If you see this, it works!
        </div>
    @else
        <div class="min-h-screen flex flex-col bg-gray-50">
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    @endauth
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
