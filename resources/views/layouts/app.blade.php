<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', setting('app_name', config('app.name'))) @php $d = setting('app_description', ''); @endphp @if($d)&mdash; {{ $d }} @endif</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        [x-cloak]{display:none!important}
        
        /* Modern Design System Overrides */
        :root {
            --primary-emerald: #059669;
            --primary-emerald-hover: #047857;
            --primary-emerald-light: #ecfdf5;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            color: #1e293b;
            background-color: var(--slate-50) !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .content-wrapper {
            background-color: #f8fafc !important;
        }

        /* Modern Navbar */
        .main-header.navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--slate-200) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03);
            padding: 0.5rem 1rem;
        }
        .main-header .nav-link {
            color: var(--slate-600) !important;
            font-weight: 500;
            transition: color 0.15s ease;
        }
        .main-header .nav-link:hover {
            color: var(--slate-900) !important;
        }
        .navbar-user-chip {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 4px 12px 4px 8px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .navbar-user-chip:hover {
            background: #e2e8f0;
            text-decoration: none;
        }
        .navbar-user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            margin-right: 8px;
        }

        /* Modern Dark Slate Sidebar */
        .main-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.06) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        .brand-link {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1rem 1.25rem !important;
            background: transparent !important;
        }
        .brand-link .brand-text {
            color: #ffffff !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em;
        }
        .nav-sidebar .nav-item {
            margin-bottom: 2px;
        }
        .nav-sidebar .nav-link {
            border-radius: 8px !important;
            margin: 1px 10px !important;
            color: #94a3b8 !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 8px 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-sidebar .nav-link i.nav-icon {
            font-size: 0.95rem;
            width: 24px;
            margin-right: 8px;
            color: #64748b;
            transition: color 0.2s ease;
        }
        .nav-sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.07) !important;
        }
        .nav-sidebar .nav-link:hover i.nav-icon {
            color: #34d399 !important;
        }
        .nav-sidebar .nav-link.active,
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
        }
        .nav-sidebar .nav-link.active i.nav-icon {
            color: #ffffff !important;
        }
        .nav-treeview .nav-link {
            padding-left: 36px !important;
            font-size: 0.84rem;
        }
        .nav-header {
            color: #64748b !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 14px 16px 4px !important;
        }

        /* Modern Card Styling */
        .card {
            border-radius: 12px !important;
            border: 1px solid var(--slate-200) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.03) !important;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            background: #ffffff;
        }
        .card.card-hover:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
            transform: translateY(-2px);
        }
        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid var(--slate-100) !important;
            font-weight: 600;
            color: var(--slate-800);
            padding: 0.9rem 1.25rem;
        }

        /* Modern Tables */
        .table {
            color: var(--slate-800);
            vertical-align: middle;
        }
        .table thead th,
        .table .thead-light th {
            background-color: #f8fafc !important;
            color: var(--slate-500) !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border-top: none !important;
            border-bottom: 1px solid var(--slate-200) !important;
            padding: 0.75rem 1rem !important;
        }
        .table td {
            padding: 0.85rem 1rem !important;
            vertical-align: middle !important;
            border-top: 1px solid #f1f5f9 !important;
            font-size: 0.875rem;
        }
        .table tbody tr {
            transition: background-color 0.15s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Modern Badges (Soft Pill Style) */
        .badge {
            font-weight: 600 !important;
            letter-spacing: 0.02em;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px !important;
            font-size: 0.75rem;
        }
        .badge-success {
            background-color: #ecfdf5 !important;
            color: #047857 !important;
            border: 1px solid #a7f3d0 !important;
        }
        .badge-primary {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border: 1px solid #bfdbfe !important;
        }
        .badge-info {
            background-color: #f0fdfa !important;
            color: #0f766e !important;
            border: 1px solid #99f6e4 !important;
        }
        .badge-warning {
            background-color: #fffbeb !important;
            color: #b45309 !important;
            border: 1px solid #fde68a !important;
        }
        .badge-danger {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
            border: 1px solid #fecaca !important;
        }
        .badge-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
        }

        /* Modern Buttons & Inputs */
        .btn {
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            border-color: #047857 !important;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
        }
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        /* Progress Bars */
        .progress {
            background-color: #f1f5f9;
            border-radius: 9999px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        }
        .progress-bar {
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 600;
            line-height: 1;
        }

        /* Modern Dropdowns */
        .dropdown-menu {
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            padding: 6px;
        }
        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-700);
            transition: background 0.15s ease, color 0.15s ease;
        }
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--slate-900);
        }
        .dropdown-item i {
            width: 18px;
            margin-right: 6px;
            color: var(--slate-400);
        }

        /* Footer */
        .main-footer {
            background-color: #ffffff !important;
            border-top: 1px solid var(--slate-200) !important;
            color: var(--slate-500) !important;
            font-size: 0.84rem;
            padding: 0.9rem 1.25rem;
        }
    </style>
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-2">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-none" style="font-size: 0.8rem;">
                    <i class="fas fa-external-link-alt mr-1"></i> Public Portal
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link p-0 dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="navbar-user-chip">
                        <span class="navbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right mt-2" aria-labelledby="userDropdown">
                    <div class="px-3 py-2 border-bottom mb-1">
                        <div class="font-weight-bold text-dark" style="font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                        <div class="mt-1"><span class="badge badge-success">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span></div>
                    </div>
                    <a href="{{ route('profile') }}" class="dropdown-item"><i class="fas fa-user-circle"></i> Profile</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt text-danger"></i> Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center d-flex align-items-center justify-content-center py-3">
            @php $brandLogo = setting('logo_path', ''); @endphp
            @if($brandLogo)
                <img src="{{ asset('storage/' . $brandLogo) }}" alt="Logo" style="max-height:36px;width:auto;object-fit:contain;">
            @else
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center mr-2 rounded-circle" style="width:34px;height:34px;background:rgba(16,185,129,0.18);border:1px solid rgba(16,185,129,0.3);color:#34d399;">
                        <i class="fas fa-flask"></i>
                    </span>
                    <span class="brand-text font-weight-bold text-white text-truncate" style="font-size:1.05rem;letter-spacing:-0.02em;">{{ setting('app_name', 'Soil Lab') }}</span>
                </div>
            @endif
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @php
                        $user = auth()->user();
                    @endphp

                    @if($user->hasRole('Admin'))
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview {{ request()->routeIs('admin.users') || request()->routeIs('admin.roles') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.users') || request()->routeIs('admin.roles') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i><p>Users & Roles<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user"></i><p>Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles') }}" class="nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-shield-alt"></i><p>Roles & Permissions</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview {{ request()->routeIs('admin.labs') || request()->routeIs('admin.locations') || request()->routeIs('admin.farmers') || request()->routeIs('admin.parameters') || request()->routeIs('admin.dropdowns') || request()->routeIs('admin.custom-fields') || request()->routeIs('admin.qr-codes') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.labs') || request()->routeIs('admin.locations') || request()->routeIs('admin.farmers') || request()->routeIs('admin.parameters') || request()->routeIs('admin.dropdowns') || request()->routeIs('admin.custom-fields') || request()->routeIs('admin.qr-codes') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-database"></i><p>Master Data<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.labs') }}" class="nav-link {{ request()->routeIs('admin.labs') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Labs</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.locations') }}" class="nav-link {{ request()->routeIs('admin.locations') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-map-marker-alt"></i><p>Locations</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.farmers') }}" class="nav-link {{ request()->routeIs('admin.farmers') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tractor"></i><p>Farmers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.parameters') }}" class="nav-link {{ request()->routeIs('admin.parameters') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-chart-bar"></i><p>Test Parameters</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.dropdowns') }}" class="nav-link {{ request()->routeIs('admin.dropdowns') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-list"></i><p>Dropdowns</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.custom-fields') }}" class="nav-link {{ request()->routeIs('admin.custom-fields') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-cogs"></i><p>Custom Fields</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.qr-codes') }}" class="nav-link {{ request()->routeIs('admin.qr-codes') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-qrcode"></i><p>QR Codes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview {{ request()->routeIs('lab.dashboard') || request()->routeIs('samples.index') || request()->routeIs('lab.queue') || request()->routeIs('lab.testing-list') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('lab.dashboard') || request()->routeIs('samples.index') || request()->routeIs('lab.queue') || request()->routeIs('lab.testing-list') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-flask"></i><p>Laboratory<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('lab.dashboard') }}" class="nav-link {{ request()->routeIs('lab.dashboard') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i><p>Lab Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('lab.queue') }}" class="nav-link {{ request()->routeIs('lab.queue') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-inbox"></i><p>Receive Samples</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('lab.testing-list') }}" class="nav-link {{ request()->routeIs('lab.testing-list') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Sample Testing</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('samples.index') }}" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-alt"></i><p>All Samples</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview {{ request()->routeIs('field.collect-sample') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('field.collect-sample') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hard-hat"></i><p>Field Operations<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('field.collect-sample') }}" class="nav-link {{ request()->routeIs('field.collect-sample') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-hand-holding"></i><p>Collect Sample</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview {{ request()->routeIs('admin.sample-requests') || request()->routeIs('admin.zone-targets') || request()->routeIs('admin.reports') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.sample-requests') || request()->routeIs('admin.zone-targets') || request()->routeIs('admin.reports') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i><p>Targets & Reports<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.zone-targets') }}" class="nav-link {{ request()->routeIs('admin.zone-targets') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-bullseye"></i><p>Zone Targets</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-chart-bar"></i><p>Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.sample-requests') }}" class="nav-link {{ request()->routeIs('admin.sample-requests') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-hand-holding-heart"></i><p>Sample Requests</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview {{ request()->routeIs('admin.settings') || request()->routeIs('admin.app-settings') || request()->routeIs('admin.front-page') || request()->routeIs('admin.menus') || request()->routeIs('admin.recommendation-rules') || request()->routeIs('admin.inventory*') || request()->routeIs('admin.notification-settings') || request()->routeIs('admin.send-notification') || request()->routeIs('admin.audit-logs') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.settings') || request()->routeIs('admin.app-settings') || request()->routeIs('admin.front-page') || request()->routeIs('admin.menus') || request()->routeIs('admin.recommendation-rules') || request()->routeIs('admin.inventory*') || request()->routeIs('admin.notification-settings') || request()->routeIs('admin.send-notification') || request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i><p>Settings<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-cog"></i><p>Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.app-settings') }}" class="nav-link {{ request()->routeIs('admin.app-settings') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-mobile-alt"></i><p>App Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.inventory') }}" class="nav-link {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Inventory</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.notification-settings') }}" class="nav-link {{ request()->routeIs('admin.notification-settings') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-cog"></i><p>Notification Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.send-notification') }}" class="nav-link {{ request()->routeIs('admin.send-notification') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-bell"></i><p>Send Notification</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.front-page') }}" class="nav-link {{ request()->routeIs('admin.front-page') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-home"></i><p>Front Page</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.menus') }}" class="nav-link {{ request()->routeIs('admin.menus') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-bars"></i><p>Menus</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.recommendation-rules') }}" class="nav-link {{ request()->routeIs('admin.recommendation-rules') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-clipboard-list"></i><p>Recommendation Rules</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs') }}" class="nav-link {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-history"></i><p>Audit Log</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole(['Lab Manager', 'Lab Technician']))
                        <li class="nav-header">LABORATORY</li>

                        <li class="nav-item has-treeview {{ request()->routeIs('lab.dashboard') || request()->routeIs('lab.queue') || request()->routeIs('lab.testing-list') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('lab.dashboard') || request()->routeIs('lab.queue') || request()->routeIs('lab.testing-list') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-flask"></i><p>Lab Operations<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('lab.dashboard') }}" class="nav-link {{ request()->routeIs('lab.dashboard') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('lab.queue') }}" class="nav-link {{ request()->routeIs('lab.queue') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-inbox"></i><p>Receive Samples</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('lab.testing-list') }}" class="nav-link {{ request()->routeIs('lab.testing-list') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Sample Testing</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('samples.index') }}" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-alt"></i><p>All Samples</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if($user->hasRole('Lab Manager'))
                        <li class="nav-header">FIELD OPERATIONS</li>

                        <li class="nav-item has-treeview {{ request()->routeIs('field.collect-sample') || request()->routeIs('field.qr-codes') || request()->routeIs('admin.qr-codes') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('field.collect-sample') || request()->routeIs('field.qr-codes') || request()->routeIs('admin.qr-codes') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hard-hat"></i><p>Field Work<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('field.collect-sample') }}" class="nav-link {{ request()->routeIs('field.collect-sample') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-hand-holding"></i><p>Collect Sample</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.qr-codes') }}" class="nav-link {{ request()->routeIs('admin.qr-codes') || request()->routeIs('field.qr-codes') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-qrcode"></i><p>QR Codes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">ADMINISTRATION</li>

                        <li class="nav-item has-treeview {{ request()->routeIs('admin.users') || request()->routeIs('admin.farmers') || request()->routeIs('admin.zone-targets') || request()->routeIs('admin.reports') || request()->routeIs('admin.inventory*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('admin.users') || request()->routeIs('admin.farmers') || request()->routeIs('admin.zone-targets') || request()->routeIs('admin.reports') || request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tasks"></i><p>Manage<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user"></i><p>Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.farmers') }}" class="nav-link {{ request()->routeIs('admin.farmers') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tractor"></i><p>Farmers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.inventory') }}" class="nav-link {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Inventory</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.zone-targets') }}" class="nav-link {{ request()->routeIs('admin.zone-targets') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-bullseye"></i><p>Zone Targets</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-chart-bar"></i><p>Reports</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @endif

                    @if($user->hasRole('Field Official'))
                        <li class="nav-header">FIELD</li>

                        <li class="nav-item has-treeview {{ request()->routeIs('field.qr-codes') || request()->routeIs('field.collect-sample') || request()->routeIs('samples.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('field.qr-codes') || request()->routeIs('field.collect-sample') || request()->routeIs('samples.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hard-hat"></i><p>Field Operations<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('field.qr-codes') }}" class="nav-link {{ request()->routeIs('field.qr-codes') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-qrcode"></i><p>QR Codes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('field.collect-sample') }}" class="nav-link {{ request()->routeIs('field.collect-sample') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-hand-holding"></i><p>Collect Sample</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('samples.index') }}" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Samples</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole('Farmer'))
                        <li class="nav-header">FARMER</li>

                        <li class="nav-item has-treeview {{ request()->routeIs('samples.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i><p>My Samples<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('samples.index') }}" class="nav-link {{ request()->routeIs('samples.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-flask"></i><p>Samples</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        @hasSection('page_title')
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('page_title')</h1>
                    </div>
                </div>
            </div>
        </section>
        @else
        <div class="pt-3"></div>
        @endif
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block"><b>Soil Testing Management System</b></div>
        <strong>&copy; {{ date('Y') }} Soil Lab</strong>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@livewireScripts
@stack('scripts')
</body>
</html>
