<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', setting('app_name', config('app.name'))) @php $d = setting('app_description', ''); @endphp @if($d)&mdash; {{ $d }} @endif</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>[x-cloak]{display:none!important}</style>
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
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <i class="fas fa-user"></i> {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('profile') }}" class="dropdown-item"><i class="fas fa-user-circle"></i> Profile</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
            @php $brandLogo = setting('logo_path', ''); @endphp
            @if($brandLogo)
                <img src="{{ asset('storage/' . $brandLogo) }}" alt="Logo" style="max-height:38px;width:auto;object-fit:contain;opacity:.9">
            @else
                <span class="brand-text font-weight-bold" style="font-size:1.1rem;letter-spacing:.5px;"><i class="fas fa-flask mr-2" style="color:#28a745;"></i>{{ setting('app_name', 'Soil Lab') }}</span>
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
