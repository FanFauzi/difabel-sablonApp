<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern Admin Sidebar Styles */
        :root {
            --sidebar-width: 240px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --sidebar-bg: #1a1a1a;
            --sidebar-hover: #2d2d2d;
            --sidebar-active: #3a3a3a;
        }

        /* Sidebar Base */
        .sidebar {
            background: var(--sidebar-bg) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border-right: none;
            position: fixed;
            /* Penting untuk sidebar mobile */
            z-index: 1030;
            /* Pastikan di atas konten lain */
            width: var(--sidebar-width);
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .bg-gradient-primary {
            background: var(--primary-gradient) !important;
        }

        .brand-logo {
            animation: fadeInDown 0.6s ease-out;
        }

        .sidebar-header h6 {
            /* Ganti h5 ke h6 sesuai kode Anda */
            font-weight: 700 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Navigation Styles */
        .sidebar-nav {
            flex: 1;
            padding-bottom: 20px;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 15px;
        }

        .nav-section-title {
            color: #888;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            padding: 6px 10px;
            border-left: 2px solid #555;
            transition: all 0.3s ease;
        }

        .nav-section-title:hover {
            color: #fff;
            border-left-color: #667eea;
        }

        /* Navigation Links */
        .nav-link {
            display: flex !important;
            align-items: center;
            padding: 8px 12px !important;
            margin: 1px 6px !important;
            border-radius: 6px !important;
            color: #ccc !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: var(--primary-gradient);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover {
            color: #fff !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .nav-link.active {
            background: var(--sidebar-active) !important;
            color: #fff !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            border-left: 3px solid #667eea;
        }

        .nav-link.active::before {
            width: 100%;
            background: var(--primary-gradient);
        }

        /* Navigation Icons */
        .nav-icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .nav-link:hover .nav-icon {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .nav-link.active .nav-icon {
            background: #667eea;
            color: #fff;
        }

        /* Navigation Text */
        .nav-text {
            flex: 1;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* Navigation Badges */
        .nav-badge {
            margin-left: auto;
        }

        .nav-badge .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Main Navigation Special Style */
        .main-nav {
            background: var(--primary-gradient) !important;
            margin: 8px !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .main-nav .nav-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .main-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Logout Navigation */
        .logout-nav {
            color: #ff6b6b !important;
            border-radius: 8px !important;
            margin: 8px !important;
        }

        .logout-nav:hover {
            background: rgba(255, 107, 107, 0.1) !important;
            color: #ff5252 !important;
        }

        .logout-nav .nav-icon {
            background: rgba(255, 107, 107, 0.2);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            background: rgba(255, 255, 255, 0.05);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 0;
            /* Padding agar tidak terlalu mepet */
        }

        /* Main Content Adjustment */
        #main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease, width 0.3s ease;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Header Enhancement */
        header {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1rem 1.5rem;
            /* Disesuaikan agar lebih pas */
            position: sticky;
            /* Membuat header tetap di atas */
            top: 0;
            z-index: 1020;
        }

        header h4 {
            color: #2d3748;
            font-weight: 600;
            margin: 0;
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-link {
            animation: slideInLeft 0.5s ease-out;
            animation-fill-mode: both;
        }

        /* Perbaiki delay agar tidak terlalu banyak */
        .nav-section:nth-child(1) .nav-link {
            animation-delay: 0.05s;
        }

        .nav-section:nth-child(2) .nav-link:nth-child(1) {
            animation-delay: 0.1s;
        }

        .nav-section:nth-child(2) .nav-link:nth-child(2) {
            animation-delay: 0.15s;
        }

        .nav-section:nth-child(2) .nav-link:nth-child(3) {
            animation-delay: 0.2s;
        }

        .nav-section:nth-child(3) .nav-link:nth-child(1) {
            animation-delay: 0.25s;
        }

        .nav-section:nth-child(3) .nav-link:nth-child(2) {
            animation-delay: 0.3s;
        }

        .nav-section:nth-child(4) .nav-link:nth-child(1) {
            animation-delay: 0.35s;
        }

        .nav-section:nth-child(4) .nav-link:nth-child(2) {
            animation-delay: 0.4s;
        }


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

        /* Hover Effects */
        .nav-link:hover .nav-icon {
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-3px);
            }

            60% {
                transform: translateY(-2px);
            }
        }

        /* Active State Enhancements */
        .nav-link.active .nav-text {
            font-weight: 600;
        }

        /* Gradient Text Effects */
        .brand-logo h6 {
            /* Pastikan ini h6 */
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* --- PERBAIKAN PENTING UNTUK MOBILE --- */
        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
                /* Efek bayangan lebih jelas */
            }

            /* Hapus display: none untuk .nav-text */
            .nav-text {
                display: block;
                /* Ubah ke block agar selalu terlihat */
                text-align: left;
                /* Teks rata kiri */
                white-space: nowrap;
                /* Pastikan teks tidak patah */
                overflow: hidden;
                /* Sembunyikan jika terlalu panjang */
                text-overflow: ellipsis;
                /* Tambahkan ellipsis jika tersembunyi */
                margin-left: 10px;
                /* Jarak antara ikon dan teks */
                font-size: 0.75rem;
                /* Sedikit lebih kecil agar muat */
            }

            .nav-link {
                justify-content: flex-start;
                /* Rata kiri */
                padding: 10px 10px !important;
                /* Padding disesuaikan */
            }

            .nav-icon {
                width: 24px;
                /* Ikon sedikit lebih kecil */
                height: 24px;
                font-size: 0.8rem;
                /* Ukuran ikon */
                margin-right: 8px;
                /* Jarak antara ikon dan teks */
            }

            /* Perbaiki posisi badge agar tidak tumpang tindih */
            .nav-badge {
                position: static;
                /* Kembali ke posisi normal di dalam flexbox */
                margin-left: auto;
                /* Dorong ke kanan */
                order: 2;
                /* Pastikan badge muncul setelah teks jika flex-direction adalah row */
            }

            /* Nav section title juga dibuat lebih baik */
            .nav-section-title {
                padding: 6px 8px;
                font-size: 0.65rem;
                border-left: none;
                /* Hapus border kiri */
                text-align: center;
                /* Rata tengah */
            }

            /* Sembunyikan brand-logo dan kecilkan header di mobile */
            .sidebar-header .brand-logo {
                display: none;
                /* Sembunyikan logo di mobile untuk menghemat ruang */
            }

            .sidebar-header h6 {
                font-size: 0.9rem;
                /* Ukuran font judul di header sidebar mobile */
                margin-bottom: 5px !important;
            }

            .sidebar-header small {
                font-size: 0.6rem !important;
            }

            /* Logout button agar tetap rapi */
            .logout-nav {
                justify-content: flex-start;
                padding: 10px 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar bg-dark text-white" id="sidebar">
            <div class="sidebar-content p-0">
                <div class="sidebar-header bg-gradient-primary text-center py-3 px-2">
                    <div class="brand-logo mb-1">
                        <i class="fas fa-crown fa-lg text-white mb-1"></i>
                        <h6 class="mb-1 fw-bold text-white">Admin Panel</h6>
                        <small class="text-white-75" style="font-size: 0.7rem;">Sistem Manajemen</small>
                    </div>
                    <hr class="bg-white opacity-25 my-2">
                </div>
                <div class="sidebar-nav px-2 py-1">
                    <div class="nav-section mb-2">
                        <a class="nav-link main-nav {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div><span
                                class="nav-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-section mb-2">
                        <div class="nav-section-title"><i class="fas fa-cogs me-1"></i><span>MANAJEMEN</span></div>
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <div class="nav-icon"><i class="fas fa-users"></i></div><span class="nav-text">Manajemen
                                User</span>
                            <div class="nav-badge"><span class="badge bg-primary"
                                    style="font-size: 0.6rem; padding: 2px 6px;">{{ \App\Models\User::count() }}</span>
                            </div>
                        </a>
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                            href="{{ route('admin.products.index') }}">
                            <div class="nav-icon"><i class="fas fa-box-open"></i></div><span class="nav-text">Manajemen
                                Produk</span>
                            <div class="nav-badge"><span class="badge bg-success"
                                    style="font-size: 0.6rem; padding: 2px 6px;">{{ \App\Models\Product::count() }}</span>
                            </div>
                        </a>
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                            href="{{ route('admin.orders.index') }}">
                            <div class="nav-icon"><i class="fas fa-shopping-cart"></i></div><span
                                class="nav-text">Manajemen Pesanan</span>
                            <div class="nav-badge"><span class="badge bg-warning"
                                    style="font-size: 0.6rem; padding: 2px 6px;">{{ \App\Models\Order::count() }}</span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-section mb-2">
                        <div class="nav-section-title"><i class="fas fa-chart-bar me-1"></i><span>LAPORAN</span></div>
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                            href="{{ route('admin.reports.financial') }}">
                            <div class="nav-icon"><i class="fas fa-chart-line"></i></div><span class="nav-text">Laporan
                                Keuangan</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                            href="{{ route('admin.activity-logs.index') }}">
                            <div class="nav-icon"><i class="fas fa-history"></i></div><span class="nav-text">Log
                                Aktivitas</span>
                        </a>
                    </div>
                    <div class="nav-section">
                        <div class="nav-section-title"><i class="fas fa-user-circle me-1"></i><span>AKUN</span></div>
                        <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
                            href="{{ route('admin.profile') }}">
                            <div class="nav-icon"><i class="fas fa-user"></i></div><span class="nav-text">Profil</span>
                        </a>
                        <hr class="my-2 border-secondary">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="nav-link logout-nav w-100 text-start border-0 bg-transparent">
                                <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div><span
                                    class="nav-text">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="sidebar-footer text-center py-2 px-2 border-top border-secondary mt-auto">
                    <small class="text-muted" style="font-size: 0.7rem;"><i
                            class="fas fa-clock me-1"></i>{{ now()->format('H:i') }}</small>
                </div>
            </div>
        </nav>

        <div class="flex-grow-1" id="main-content">
            <header class="bg-light p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-outline-secondary d-md-none me-2" id="sidebar-toggler">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0 flex-grow-1">@yield('title', 'Dashboard')</h4>
                    <div>
                        <span class="me-3">Welcome, {{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="p-4">
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggler = document.getElementById('sidebar-toggler');

            if (toggler) {
                toggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggler = toggler && toggler.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggler && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>