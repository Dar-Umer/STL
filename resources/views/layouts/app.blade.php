<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('app_name', config('app.name', 'Soil Lab'))) - Admin</title>

    <!-- Google Fonts: Inter (Single Canonical Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        mono: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }

        /* Universal Single Font Enforcement: Inter everywhere across the admin panel, strictly excluding FontAwesome icons */
        *:not([class*="fa-"]):not([class^="fa-"]):not(.fa):not(.fas):not(.far):not(.fal):not(.fad):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands),
        *:not([class*="fa-"]):not([class^="fa-"]):not(.fa):not(.fas):not(.far):not(.fal):not(.fad):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands)::before,
        *:not([class*="fa-"]):not([class^="fa-"]):not(.fa):not(.fas):not(.far):not(.fal):not(.fad):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands)::after {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        html, body, input, button, select, textarea, optgroup, table, th, td, h1, h2, h3, h4, h5, h6, p, label, kbd, code, pre {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        span:not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab):not(.fa-solid):not(.fa-regular), 
        a:not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab):not(.fa-solid):not(.fa-regular),
        i:not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab):not(.fa-solid):not(.fa-regular) {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Preserve and enforce FontAwesome icons across all variations and pseudo-elements */
        [class*="fa-"], [class^="fa-"], .fa, .fas, .far, .fal, .fad, .fab, .fa-solid, .fa-regular, .fa-brands,
        [class*="fa-"]::before, [class^="fa-"]::before, .fa::before, .fas::before, .far::before, .fal::before, .fad::before, .fab::before, .fa-solid::before, .fa-regular::before, .fa-brands::before,
        [class*="fa-"]::after, [class^="fa-"]::after, .fa::after, .fas::after, .far::after, .fal::after, .fad::after, .fab::after, .fa-solid::after, .fa-regular::after, .fa-brands::after {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome" !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .fa, .fas, .fa-solid, 
        .fa::before, .fas::before, .fa-solid::before {
            font-weight: 900 !important;
            font-family: "Font Awesome 6 Free" !important;
        }

        .far, .fa-regular, 
        .far::before, .fa-regular::before {
            font-weight: 400 !important;
            font-family: "Font Awesome 6 Free" !important;
        }

        .fab, .fa-brands, 
        .fab::before, .fa-brands::before {
            font-weight: 400 !important;
            font-family: "Font Awesome 6 Brands" !important;
        }

        /* Harmonize monospace/code badges to Inter with tabular numbers for consistent single font */
        .font-mono {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            font-feature-settings: 'tnum' 1;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.025em;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    @php
        $user = auth()->user();
        $brandLogo = setting('logo_path', '');
        $appName = setting('app_name', 'Soil Lab');

        $isItemActive = fn($r) => request()->routeIs($r) || (str_ends_with($r, '.index') && request()->routeIs(substr($r, 0, -6) . '.*'));

        // Navigation Structure definition
        $groups = [];

        // Direct Dashboard
        if ($user?->hasRole('Admin')) {
            $groups[] = [
                'label' => null,
                'items' => [
                    [
                        'route' => 'admin.dashboard',
                        'label' => 'Dashboard',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                    ],
                ],
            ];

            // 1. Laboratory & Testing
            $groups[] = [
                'label' => 'Laboratory & Testing',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.942A4.5 4.5 0 0115.888 17H8.112a4.5 4.5 0 01-2.342-.758L4.2 15.3m15.6 0A2.25 2.25 0 0021 13.368V7.5a2.25 2.25 0 00-2.25-2.25h-1.5M4.2 15.3A2.25 2.25 0 013 13.368V7.5a2.25 2.25 0 012.25-2.25h1.5"/>',
                'items' => [
                    ['route' => 'lab.dashboard', 'label' => 'Lab Dashboard'],
                    ['route' => 'lab.queue', 'label' => 'Receive Samples (Intake)'],
                    ['route' => 'lab.evaluations', 'label' => 'Evaluations & Lab Sheets'],
                    ['route' => 'lab.testing-list', 'label' => 'Testing Workbench'],
                    ['route' => 'samples.index', 'label' => 'All Soil Samples'],
                ],
            ];

            // 2. Field Operations
            $groups[] = [
                'label' => 'Field Operations',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
                'items' => [
                    ['route' => 'field.collect-sample', 'label' => 'Collect Field Sample'],
                    ['route' => 'admin.qr-codes', 'label' => 'QR Code Generation'],
                ],
            ];

            // 3. Master Records & CRM
            $groups[] = [
                'label' => 'Master Records',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>',
                'items' => [
                    ['route' => 'admin.farmers', 'label' => 'Farmers Directory'],
                    ['route' => 'admin.labs', 'label' => 'Testing Facilities (Labs)'],
                    ['route' => 'admin.locations', 'label' => 'Geographic Zones & Blocks'],
                    ['route' => 'admin.parameters', 'label' => 'Soil Test Parameters'],
                    ['route' => 'admin.dropdowns', 'label' => 'Dynamic Dropdowns'],
                    ['route' => 'admin.custom-fields', 'label' => 'Custom Form Fields'],
                ],
            ];

            // 4. Targets & Reports
            $groups[] = [
                'label' => 'Targets & Reports',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                'items' => [
                    ['route' => 'admin.zone-targets', 'label' => 'Zone Targets'],
                    ['route' => 'admin.reports', 'label' => 'Analytics Reports'],
                    ['route' => 'admin.sample-requests', 'label' => 'Sample Requests'],
                ],
            ];

            // 5. Inventory & Reagents
            $groups[] = [
                'label' => 'Inventory & Reagents',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>',
                'items' => [
                    ['route' => 'admin.inventory', 'label' => 'Chemicals & Reagents'],
                ],
            ];

            // 6. Settings & System Administration
            $groups[] = [
                'label' => 'Settings & System',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'items' => [
                    ['route' => 'admin.users', 'label' => 'User Management'],
                    ['route' => 'admin.roles', 'label' => 'Roles & Permissions'],
                    ['route' => 'admin.settings', 'label' => 'System Settings'],
                    ['route' => 'admin.app-settings', 'label' => 'Mobile App Settings'],
                    ['route' => 'admin.notification-settings', 'label' => 'Notification Rules'],
                    ['route' => 'admin.send-notification', 'label' => 'Send Push Broadcast'],
                    ['route' => 'admin.recommendation-rules', 'label' => 'Fertilizer Recommendation Rules'],
                    ['route' => 'admin.front-page', 'label' => 'Landing Page Content'],
                    ['route' => 'admin.menus', 'label' => 'Portal Menus'],
                    ['route' => 'admin.audit-logs', 'label' => 'Audit Trail Logs'],
                ],
            ];
        } elseif ($user?->hasRole(['Lab Manager', 'Lab Technician'])) {
            $groups[] = [
                'label' => null,
                'items' => [
                    [
                        'route' => 'lab.dashboard',
                        'label' => 'Lab Dashboard',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                    ],
                ],
            ];

            $groups[] = [
                'label' => 'Lab Operations',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.942A4.5 4.5 0 0115.888 17H8.112a4.5 4.5 0 01-2.342-.758L4.2 15.3m15.6 0A2.25 2.25 0 0021 13.368V7.5a2.25 2.25 0 00-2.25-2.25h-1.5M4.2 15.3A2.25 2.25 0 013 13.368V7.5a2.25 2.25 0 012.25-2.25h1.5"/>',
                'items' => [
                    ['route' => 'lab.queue', 'label' => 'Receive Samples'],
                    ['route' => 'lab.evaluations', 'label' => 'Evaluations & Sheets'],
                    ['route' => 'lab.testing-list', 'label' => 'Sample Testing'],
                    ['route' => 'samples.index', 'label' => 'All Samples'],
                ],
            ];

            if ($user?->hasRole('Lab Manager')) {
                $groups[] = [
                    'label' => 'Management',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>',
                    'items' => [
                        ['route' => 'admin.users', 'label' => 'Users'],
                        ['route' => 'admin.farmers', 'label' => 'Farmers'],
                        ['route' => 'admin.inventory', 'label' => 'Inventory'],
                        ['route' => 'admin.zone-targets', 'label' => 'Zone Targets'],
                        ['route' => 'admin.reports', 'label' => 'Reports'],
                    ],
                ];
            }
        } elseif ($user?->hasRole('Field Official')) {
            $groups[] = [
                'label' => 'Field Operations',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
                'items' => [
                    ['route' => 'field.collect-sample', 'label' => 'Collect Sample'],
                    ['route' => 'field.qr-codes', 'label' => 'QR Codes'],
                    ['route' => 'samples.index', 'label' => 'Samples'],
                ],
            ];
        } else {
            $groups[] = [
                'label' => 'Portal',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                'items' => [
                    ['route' => 'samples.index', 'label' => 'My Samples'],
                ],
            ];
        }
    @endphp

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Mobile Overlay Backdrop -->
        <div x-show="sidebarOpen" x-cloak style="display: none;"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-gray-900/60 backdrop-blur-xs lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Modern Dark Sidebar (PTA Style) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-gray-300 transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto flex flex-col border-r border-gray-800">

            <!-- Branding Header -->
            <div class="flex items-center justify-between h-20 px-6 bg-gray-900 border-b border-gray-800 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-1 group">
                    @if($brandLogo)
                        <img src="{{ asset('storage/' . $brandLogo) }}" alt="{{ $appName }}" class="h-10 max-h-12 w-auto max-w-[170px] object-contain rounded-lg">
                    @else
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-brand-400 tracking-tight">Soil</span>
                            <span class="text-2xl font-light text-white tracking-tight ml-0.5">Lab</span>
                        </div>
                    @endif
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
                @foreach ($groups as $group)
                    @if($group['label'] === null)
                        {{-- Direct Items without accordion --}}
                        <div class="space-y-1">
                            @foreach($group['items'] as $item)
                                @if(Route::has($item['route']))
                                    @php
                                        $isActive = $isItemActive($item['route']);
                                    @endphp
                                    <a href="{{ route($item['route']) }}"
                                       class="relative flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ $isActive ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                        <span class="flex items-center gap-3">
                                            <svg class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $item['icon'] !!}
                                            </svg>
                                            <span>{{ $item['label'] }}</span>
                                        </span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <div class="my-2.5 border-t border-gray-800 opacity-80 mx-1"></div>
                    @else
                        {{-- Collapsible Groups --}}
                        @php
                            $hasActive = collect($group['items'])->contains(fn($i) => Route::has($i['route']) && $isItemActive($i['route']));
                            $visibleRoutes = collect($group['items'])->filter(fn($i) => Route::has($i['route']));
                        @endphp

                        @if($visibleRoutes->isNotEmpty())
                            <div x-data="{ open: {{ $hasActive ? 'true' : 'false' }} }" class="mt-0.5">
                                <button @click="open = !open"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ $hasActive ? 'text-white font-semibold bg-white/5' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    <span class="flex items-center gap-3 min-w-0">
                                        <svg class="w-5 h-5 flex-shrink-0 {{ $hasActive ? 'text-emerald-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $group['icon'] !!}
                                        </svg>
                                        <span class="truncate whitespace-nowrap">{{ $group['label'] }}</span>
                                    </span>
                                    <svg class="w-4 h-4 flex-shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-90 text-white' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-0.5 border-l border-gray-800 pl-3.5">
                                    @foreach($group['items'] as $item)
                                        @if(Route::has($item['route']))
                                            <a href="{{ route($item['route']) }}"
                                               class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ $isItemActive($item['route']) ? 'bg-emerald-500/15 text-emerald-400 font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                                <span>{{ $item['label'] }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </nav>

            <!-- Sidebar Footer: User Profile & Fast Logout (PTA style) -->
            <div class="p-3 border-t border-gray-800 flex items-center justify-between mt-auto bg-gray-900/90 flex-shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center text-xs flex-shrink-0">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ $user?->name ?? 'Staff User' }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $user?->getRoleNames()->first() ?? 'Authorized' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Sign out" class="p-1.5 text-gray-400 hover:text-rose-400 hover:bg-white/5 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Shell Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header (PTA style: h-20 bg-white border-b border-gray-200) -->
            <header class="bg-white shadow-xs border-b border-gray-200 h-20 flex items-center justify-between px-4 lg:px-6 flex-shrink-0 z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <h1 class="text-lg font-bold text-gray-800 hidden sm:block tracking-tight">
                        @yield('page_title', 'Soil Intelligence Network')
                    </h1>

                    {{-- Quick Search (Ctrl+K) Trigger --}}
                    <button @click="window.dispatchEvent(new CustomEvent('open-command-palette'))"
                            type="button"
                            class="sm:hidden p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition cursor-pointer"
                            title="Search pages or samples (Ctrl+K)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </button>

                    <button @click="window.dispatchEvent(new CustomEvent('open-command-palette'))"
                            type="button"
                            class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 text-xs text-gray-400 bg-gray-50 hover:bg-gray-100 hover:text-gray-700 border border-gray-200 rounded-xl transition shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        <span>Search samples, farmers, labs...</span>
                        <kbd class="hidden md:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-gray-500 bg-white border border-gray-200 rounded shadow-2xs font-mono">⌘K</kbd>
                    </button>
                </div>

                <div class="flex items-center space-x-3">
                    {{-- Quick Create Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition active:scale-95 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Create</span>
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl z-50 border border-gray-100 py-1.5 divide-y divide-gray-50">
                            <div class="py-1">
                                <a href="{{ route('field.collect-sample') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">SMP</span>
                                    Collect Field Sample
                                </a>
                                <a href="{{ route('admin.farmers') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">FRM</span>
                                    Register Farmer
                                </a>
                                <a href="{{ route('admin.parameters') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="w-6 h-6 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center text-xs font-bold">PRM</span>
                                    Define Parameter
                                </a>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.labs') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">LAB</span>
                                    Add Laboratory
                                </a>
                                <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">USR</span>
                                    New User Account
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Page Speed / Diagnostic Pill --}}
                    @php $pageLoadMs = defined('LARAVEL_START') ? round((microtime(true) - LARAVEL_START) * 1000) : 12; @endphp
                    <div x-data="{ loadMs: '{{ $pageLoadMs }}' }"
                         x-init="window.addEventListener('load', () => { const nav = performance.getEntriesByType('navigation')[0]; if (nav && nav.duration) loadMs = Math.round(nav.duration); })">
                        <div class="hidden md:flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl shadow-2xs">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-gray-400 font-normal">Load</span>
                            <span class="font-bold text-gray-800" x-text="loadMs + 'ms'"></span>
                        </div>
                    </div>

                    {{-- Notifications Bell --}}
                    @php
                        $unreadNotifications = collect();
                        $unreadCount = 0;
                        if ($user) {
                            try {
                                $unreadCount = \App\Models\Notification::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->count();
                                $unreadNotifications = \App\Models\Notification::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            } catch (\Throwable $e) {
                                $unreadNotifications = collect();
                                $unreadCount = 0;
                            }
                        }
                    @endphp
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-100 transition focus:outline-none" title="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl z-50 border border-gray-100 overflow-hidden divide-y divide-gray-50">
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50/50">
                                <p class="text-sm font-semibold text-gray-900">Notifications</p>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $unreadCount }} new
                                </span>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                                @forelse($unreadNotifications as $notif)
                                    <div class="px-4 py-3 hover:bg-gray-50 transition">
                                        <p class="text-xs font-semibold text-gray-800">{{ $notif->title }}</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $notif->body }}</p>
                                        <span class="text-[10px] text-gray-400 mt-1 block">{{ $notif->created_at?->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-xs text-gray-400">
                                        No unread notifications at this moment.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- User Profile Menu --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm text-gray-600 hover:text-gray-900 focus:outline-none p-1.5 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-emerald-400 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:inline font-semibold text-gray-800 text-xs">{{ $user?->name ?? 'Admin' }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-1.5 z-50 border border-gray-100 divide-y divide-gray-50">
                            <div class="px-4 py-3">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $user?->name ?? 'Admin User' }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ $user?->email ?? '' }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $user?->getRoleNames()->first() ?? 'Member' }}
                                </span>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-emerald-700 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page Content Area (PTA layout container) -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8 relative bg-gray-50/70">
                {{-- Floating Toast Notifications (PTA style auto-dismiss) --}}
                @if(session('success'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-gray-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-gray-800 max-w-md">
                        <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="text-xs font-medium flex-1">
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 7000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-gray-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-rose-900/50 max-w-md">
                        <div class="w-7 h-7 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="text-xs font-medium flex-1">
                            {{ session('error') }}
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Modern Footer -->
            <footer class="flex-shrink-0 px-4 lg:px-6 py-3 border-t border-gray-200 bg-white">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ $appName }} &mdash; Soil Testing &amp; Fertility Intelligence</p>
                    <p class="text-xs text-gray-400">Agricultural Lab Operations Portal</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Global Command Palette (Cmd/Ctrl + K) --}}
    <script>
        window.adminCommandPalette = function() {
            return {
                isOpen: false,
                search: '',
                selectedIndex: 0,
                items: [
                    { title: 'Admin Dashboard', category: 'Overview', url: '{{ route('admin.dashboard') }}' },
                    { title: 'All Soil Samples', category: 'Samples & Testing', url: '{{ route('samples.index') }}' },
                    { title: 'Receive Samples (Intake Queue)', category: 'Laboratory', url: '{{ route('lab.queue') }}' },
                    { title: 'Evaluation & Lab Codes (Worksheets)', category: 'Laboratory', url: '{{ route('lab.evaluations') }}' },
                    { title: 'Sample Testing Workbench', category: 'Laboratory', url: '{{ route('lab.testing-list') }}' },
                    { title: 'Collect New Field Sample', category: 'Field Operations', url: '{{ route('field.collect-sample') }}' },
                    { title: 'QR Code Generator', category: 'Field Operations', url: '{{ route('admin.qr-codes') }}' },
                    { title: 'Farmers Directory', category: 'Master Records', url: '{{ route('admin.farmers') }}' },
                    { title: 'Laboratories & Testing Centers', category: 'Master Records', url: '{{ route('admin.labs') }}' },
                    { title: 'Locations, Districts & Zones', category: 'Master Records', url: '{{ route('admin.locations') }}' },
                    { title: 'Soil Test Parameters', category: 'Master Records', url: '{{ route('admin.parameters') }}' },
                    { title: 'Zone Targets Tracking', category: 'Reports & Targets', url: '{{ route('admin.zone-targets') }}' },
                    { title: 'Analytics & Performance Reports', category: 'Reports & Targets', url: '{{ route('admin.reports') }}' },
                    { title: 'Public Sample Requests', category: 'Reports & Targets', url: '{{ route('admin.sample-requests') }}' },
                    { title: 'Chemicals & Reagents Inventory', category: 'Inventory', url: '{{ route('admin.inventory') }}' },
                    { title: 'User Accounts Management', category: 'Administration', url: '{{ route('admin.users') }}' },
                    { title: 'Roles & Permissions', category: 'Administration', url: '{{ route('admin.roles') }}' },
                    { title: 'System Settings', category: 'Administration', url: '{{ route('admin.settings') }}' },
                    { title: 'Mobile App Settings', category: 'Administration', url: '{{ route('admin.app-settings') }}' },
                    { title: 'Fertilizer Recommendation Rules', category: 'Administration', url: '{{ route('admin.recommendation-rules') }}' },
                    { title: 'Audit Trail Logs', category: 'Administration', url: '{{ route('admin.audit-logs') }}' },
                ],
                init() {
                    this.$watch('search', () => { this.selectedIndex = 0; });
                },
                get filtered() {
                    if (!this.search || !this.search.trim()) return this.items;
                    var q = this.search.trim().toLowerCase();
                    return this.items.filter(function(i) {
                        return i.title.toLowerCase().includes(q) || i.category.toLowerCase().includes(q);
                    });
                },
                open() {
                    this.isOpen = true;
                    this.search = '';
                    this.selectedIndex = 0;
                    this.$nextTick(() => { if (this.$refs.paletteInput) this.$refs.paletteInput.focus(); });
                },
                close() { this.isOpen = false; },
                toggle() { this.isOpen ? this.close() : this.open(); },
                selectNext() {
                    if (this.filtered.length === 0) return;
                    this.selectedIndex = (this.selectedIndex + 1) % this.filtered.length;
                },
                selectPrev() {
                    if (this.filtered.length === 0) return;
                    this.selectedIndex = (this.selectedIndex - 1 + this.filtered.length) % this.filtered.length;
                },
                chooseSelected() {
                    if (this.filtered.length > 0 && this.filtered[this.selectedIndex]) {
                        window.location.href = this.filtered[this.selectedIndex].url;
                    }
                }
            };
        }
    </script>

    <div x-data="adminCommandPalette()"
         x-init="init()"
         x-cloak
         x-show="isOpen"
         style="display: none;"
         @open-command-palette.window="open()"
         @keydown.window.prevent.cmd.k="toggle()"
         @keydown.window.prevent.ctrl.k="toggle()"
         @keydown.window.escape="close()"
         class="fixed inset-0 z-[120] flex items-start justify-center pt-16 px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click="close()" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-xl overflow-hidden z-10 flex flex-col max-h-[80vh]">
            <div class="flex items-center px-4 py-3.5 border-b border-gray-100 gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input x-ref="paletteInput"
                       x-model="search"
                       @keydown.arrow-down.prevent="selectNext()"
                       @keydown.arrow-up.prevent="selectPrev()"
                       @keydown.enter.prevent="chooseSelected()"
                       type="text"
                       placeholder="Jump to sample, screen, setting, or report..."
                       class="w-full text-sm text-gray-800 placeholder-gray-400 border-0 focus:outline-none focus:ring-0">
                <kbd class="px-2 py-0.5 text-[10px] font-semibold text-gray-400 bg-gray-50 border border-gray-200 rounded-lg">ESC</kbd>
            </div>

            <div class="overflow-y-auto p-2 divide-y divide-gray-50">
                <template x-for="(item, index) in filtered" :key="item.url">
                    <a :href="item.url"
                       :class="index === selectedIndex ? 'bg-emerald-50 text-emerald-900' : 'text-gray-700 hover:bg-gray-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span x-text="item.title" class="font-semibold"></span>
                        </div>
                        <span x-text="item.category" class="text-[10px] uppercase tracking-wider text-gray-400"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
