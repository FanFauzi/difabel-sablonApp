<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard User')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark text-white position-fixed" id="userSidebar"
            style="width: 220px; height: 100vh; overflow-y: auto; z-index: 1000;">
            <div class="p-2">

                <!-- User Profile -->
                <div class="user-profile mb-3 p-3 bg-gradient-primary text-center rounded">
                    <div class="profile-avatar mb-2">
                        <div class="avatar-circle mx-auto">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="fw-bold text-white mb-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                        <div class="text-white-75" style="font-size: 0.75rem;">{{ Str::limit(Auth::user()->email, 20) }}
                        </div>
                    </div>
                    <hr class="bg-white opacity-25 my-3">
                </div>

                <!-- Main Menu -->
                <div class="mb-3">
                    <div class="menu-title text-uppercase small fw-bold text-white-75 mb-2 px-2"
                        style="font-size: 0.7rem;">
                        <i class="fas fa-th-large me-1"></i>Menu Utama
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('user.home') }}">
                                <i class="fa-solid fa-house me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Home</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Order Management -->
                <div class="mb-3">
                    <div class="menu-title text-uppercase small fw-bold text-white-75 mb-2 px-2"
                        style="font-size: 0.7rem;">
                        <i class="fas fa-shopping-cart me-1"></i>Pesanan
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('user.products') ? 'active' : '' }}"
                                href="{{ route('user.products') }}">
                                <i class="fas fa-plus-circle me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Buat Pesanan</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('user.check-status') ? 'active' : '' }}"
                                href="{{ route('user.check-status') }}">
                                <i class="fas fa-search me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Cek Status</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('user.orders') && !request()->routeIs('user.orders.show') ? 'active' : '' }}"
                                href="{{ route('user.orders') }}">
                                <i class="fas fa-history me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Account Management -->
                <div class="mb-3">
                    <div class="menu-title text-uppercase small fw-bold text-white-75 mb-2 px-2"
                        style="font-size: 0.7rem;">
                        <i class="fas fa-user-cog me-1"></i>Akun
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link text-white {{ request()->routeIs('user.profile') ? 'active' : '' }}"
                                href="{{ route('user.profile') }}">
                                <i class="fas fa-user-edit me-2" style="font-size: 0.9rem;"></i>
                                <span style="font-size: 0.8rem;">Profil</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Logout Section -->
                <div class="mt-auto">
                    <hr class="my-2 border-white opacity-25">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit"
                                    class="nav-link logout-link text-white bg-transparent border-0 text-start w-100 p-2">
                                    <i class="fas fa-sign-out-alt me-2" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.8rem;">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1 bg-light" style="margin-left: 220px;">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm px-4 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                    <div class="d-flex align-items-center">
                        <small class="text-muted me-3">{{ now()->format('l, d F Y') }}</small>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user me-2"></i>Profil
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white border-top py-3 mt-5">
                <div class="container-fluid text-center">
                    <small class="text-muted">© 2025 Sablon Custom - Sistem Pemesanan Online</small>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Compact User Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border-right: 1px solid #495057;
        }

        .user-profile {
            border: none !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .avatar-circle i {
            font-size: 1.2rem;
            color: #fff;
        }

        .profile-info {
            animation: fadeInUp 0.8s ease-out;
        }

        .menu-title {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.7rem !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px !important;
            padding: 4px 8px !important;
        }

        .nav-link {
            display: flex !important;
            align-items: center;
            padding: 6px 10px !important;
            margin: 1px 4px !important;
            border-radius: 6px !important;
            color: rgba(255, 255, 255, 0.9) !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
            font-size: 0.8rem !important;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
            transform: translateX(3px);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25) !important;
            color: #fff !important;
            border-left: 3px solid #fff;
            font-weight: 600;
        }

        .logout-link {
            color: #ffcccc !important;
            font-weight: 500;
        }

        .logout-link:hover {
            background-color: rgba(255, 204, 204, 0.1) !important;
            color: #fff !important;
        }

        /* Main Content Adjustments */
        .flex-grow-1 {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Top Bar Compact */
        .bg-white.shadow-sm {
            padding: 0.75rem 1.5rem !important;
        }

        .bg-white.shadow-sm h4 {
            font-size: 1.25rem !important;
            margin: 0 !important;
        }

        /* Content Area */
        .p-4 {
            padding: 1.5rem !important;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        /* Button Styles */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            border: none;
            font-size: 0.9rem;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            margin-top: 0.5rem !important;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Footer */
        footer {
            margin-top: 2rem !important;
            padding: 1rem 0 !important;
        }

        footer small {
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .flex-grow-1 {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .nav-link span {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 8px !important;
            }

            .menu-title {
                text-align: center;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>
