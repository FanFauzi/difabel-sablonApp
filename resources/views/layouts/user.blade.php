<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* (CSS Anda yang lain bisa ditaruh di sini atau di file terpisah) */
        
        /* Gaya Sidebar Utama */
        .sidebar {
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border-right: 1px solid #495057;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-left: 3px solid #fff;
            font-weight: 600;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(2px);
        }
        
        .logout-link:hover {
            background-color: rgba(255, 100, 100, 0.1) !important;
        }

        /* Gaya Konten Utama */
        #main-content {
            margin-left: 220px; /* Jarak untuk sidebar di desktop */
            transition: margin-left 0.3s ease;
            width: calc(100% - 220px);
        }

        /* PERBAIKAN PENTING UNTUK TAMPILAN MOBILE */
        @media (max-width: 767.98px) {
            #main-content {
                margin-left: 0 !important; /* Hapus margin di mobile */
                width: 100%;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar bg-dark text-white position-fixed" id="userSidebar" style="width: 220px; height: 100vh; overflow-y: auto; z-index: 1030;">
            <div class="p-2 d-flex flex-column h-100">
                <div class="user-profile mb-3 p-3 text-center rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="fw-bold text-white mb-1">{{ Auth::user()->name }}</div>
                    <div class="text-white-75 small">{{ Str::limit(Auth::user()->email, 20) }}</div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
                            <i class="fas fa-home fa-fw me-2"></i>Home
                        </a>
                    </li>
                </ul>

                <hr class="text-secondary">
                
                <ul class="nav flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white {{ request()->routeIs('user.products*') ? 'active' : '' }}" href="{{ route('user.products') }}">
                            <i class="fas fa-plus-circle fa-fw me-2"></i>Buat Pesanan
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white {{ request()->routeIs('user.orders*') ? 'active' : '' }}" href="{{ route('user.orders') }}">
                            <i class="fas fa-history fa-fw me-2"></i>Riwayat Pesanan
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-white {{ request()->routeIs('user.check-status') ? 'active' : '' }}" href="{{ route('user.check-status') }}">
                            <i class="fas fa-search fa-fw me-2"></i>Cek Status
                        </a>
                    </li>
                </ul>

                <div class="mt-auto">
                    <hr class="text-secondary">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                             <a class="nav-link text-white {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                                <i class="fas fa-user-edit fa-fw me-2"></i>Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link text-danger bg-transparent border-0 w-100 text-start logout-link">
                                    <i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 bg-light" id="main-content">
            <header class="bg-white shadow-sm px-4 py-3 border-bottom sticky-top">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-outline-secondary d-md-none" id="sidebar-toggler">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0 d-none d-md-block flex-grow-1">@yield('title', 'Dashboard')</h4>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>

            <footer class="bg-white border-top py-3 mt-auto">
                <div class="container-fluid text-center">
                    <small class="text-muted">© {{ date('Y') }} Sablon Custom - Sistem Pemesanan Online</small>
                </div>
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('userSidebar');
            const toggler = document.getElementById('sidebar-toggler');
            
            if (toggler) {
                toggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggler = toggler.contains(event.target);
                if (!isClickInsideSidebar && !isClickOnToggler && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>