<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIJA Parking')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 260px;
            --body-bg: #f4f5f7;
            --text-dark: #344767;
            --text-muted: #a0aec0;
            --accent-pink: #8e24aa;
            --accent-gradient: linear-gradient(135deg, #d81b60, #8e24aa);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: transparent;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 32px 32px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--text-dark);
        }

        .sidebar-brand-text {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: 0.5px;
        }

        .sidebar-heading {
            padding: 16px 32px 8px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            flex: 1;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 16px;
            margin: 0 24px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-link.active {
            background: #fff;
            color: var(--text-dark);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            font-weight: 600;
        }

        .nav-link .nav-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            color: var(--text-muted);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: var(--transition);
        }

        .nav-link.active .nav-icon {
            background: var(--accent-gradient);
            color: #fff;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar-wrapper {
            padding: 24px 32px 0;
        }

        .topbar {
            background: #fff;
            border-radius: 16px;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--card-shadow);
        }

        .topbar-breadcrumb {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .breadcrumb-links {
            font-size: 12px;
            color: var(--text-muted);
        }

        .breadcrumb-links span.active-crumb {
            color: var(--text-dark);
        }

        .breadcrumb-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 2px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 9px 16px 9px 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            width: 200px;
            background: #fff;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: #d81b60;
        }

        .search-box input::placeholder {
            color: #cbd5e0;
        }

        .search-box .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e0;
            font-size: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: #fff;
            box-shadow: 0 4px 12px rgba(216, 27, 96, 0.3);
            text-transform: uppercase;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(216, 27, 96, 0.4);
        }

        .btn-signout {
            color: var(--text-dark);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-signout i {
            color: var(--text-muted);
        }

        .page-content {
            padding: 24px 32px 32px;
            flex: 1;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 24px 24px 16px;
        }

        .card-header-title {
            font-size: 18px;
            font-weight: 700;
            color: #e1008c;
        }

        .card-header-title span {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 14px;
        }

        .card-body {
            padding: 0 24px 24px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            padding: 12px 16px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--accent-pink);
            border-bottom: 1px solid #f0f2f5;
            text-align: left;
        }

        .data-table tbody td {
            padding: 16px;
            font-size: 13px;
            color: var(--text-dark);
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .footer {
            padding: 20px 32px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: auto;
        }

        .footer a {
            color: var(--text-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .footer .heart {
            color: #e91e8c;
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/parkir.png') }}"
                class="navbar-brand-img"
                alt="main_logo"
                style="width: 50px; height: 50px; object-fit; contain;">
            <span class="ms-2 font-weight-bold">PARKIR SIJA</span>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-map-marker-alt"></i></span>
                    Location
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-exchange-alt"></i></span>
                    Transaction
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vehicle-types.index') }}" class="nav-link {{ request()->routeIs('vehicle-types.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-rocket"></i></span>
                    Vehicle Type
                </a>
            </li>

            <li class="sidebar-heading">REPORTS</li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                    Location Report
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                    Transaction Report
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <div class="topbar-wrapper">
            <nav class="topbar">
                <div class="topbar-breadcrumb">
                    <div class="breadcrumb-links">
                        Pages <span style="margin: 0 4px;">/</span> <span class="active-crumb">@yield('breadcrumb', 'Dashboard')</span>
                    </div>
                    <div class="breadcrumb-title">@yield('page-title', 'Dashboard')</div>
                </div>
                <div class="topbar-actions">
                    @yield('topbar-actions')
                    <a href="#" class="btn-signout">
                        <i class="fas fa-user"></i> Sign Out
                    </a>
                </div>
            </nav>
        </div>

        <div class="page-content">
            @yield('content')
        </div>

        <footer class="footer">
            &copy; {{ date('Y') }}, made with by
            <a href="#">Fadil</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
