<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sablon-App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        .animate-slide-down {
            animation: slide-down 0.3s ease-out forwards;
        }

        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800 scroll-smooth">

    <nav class="fixed top-0 left-0 w-full bg-white shadow z-50" data-aos="fade-down">
        <div class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">
            <a href="#" class="text-2xl font-bold text-blue-600">
                Sablon-App
            </a>

            <ul class="hidden md:flex gap-6 text-gray-700 font-medium">
                <li><a href="#fitur" class="hover:text-blue-600 transition">Fitur</a></li>
                <li><a href="#produk" class="hover:text-blue-600 transition">Produk</a></li>
                <li><a href="#tentang" class="hover:text-blue-600 transition">Tentang</a></li>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                        </li>
                    @else
                        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                        </li>
                    @endif
                @endauth
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition">Login</a></li>
                @endguest
            </ul>

            <button id="mobile-menu-btn" class="md:hidden focus:outline-none">
                <svg id="menu-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-icon-close" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-inner border-t border-gray-200">
            <ul class="flex flex-col py-4 space-y-3 text-gray-700 font-medium px-6">
                <li><a href="#fitur" class="hover:text-blue-600 transition mobile-link">Fitur</a></li>
                <li><a href="#produk" class="hover:text-blue-600 transition mobile-link">Produk</a></li>
                <li><a href="#tentang" class="hover:text-blue-600 transition mobile-link">Tentang</a></li>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="hover:text-blue-600 transition mobile-link">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}"
                                class="hover:text-blue-600 transition mobile-link">Dashboard</a></li>
                    @endif
                @endauth
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition mobile-link">Login</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <section class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-24 px-6 text-center mt-[64px]"
        data-aos="fade-up">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Selamat Datang di Sablon-Apppppppppp
        </h1>
        <p class="text-lg md:text-xl mb-6 max-w-2xl mx-auto">
            Platform terbaik untuk membuat kaos sablon berkualitas tinggi
            dan desain custom sesuai keinginan Anda.
        </p>
        <a href="./product" class="bg-white text-blue-600 px-6 py-3 rounded-lg shadow hover:bg-gray-100 transition">
            Lihat Produk
        </a>
    </section>

    <section id="fitur" class="py-16 px-6 max-w-6xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl font-bold text-center mb-12">Kenapa Memilih Sablon-App?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition" data-aos-delay="100">
                <h3 class="text-xl font-semibold mb-2">Desain Custom</h3>
                <p>Buat desain kaos unik sesuai selera Anda dengan editor online kami.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition" data-aos-delay="200">
                <h3 class="text-xl font-semibold mb-2">Bahan Premium</h3>
                <p>Gunakan kaos dengan bahan premium yang nyaman dipakai dan awet.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition" data-aos-delay="300">
                <h3 class="text-xl font-semibold mb-2">Pengiriman Cepat</h3>
                <p>Pesanan Anda dikirim dengan cepat dan aman ke seluruh Indonesia.</p>
            </div>
        </div>
    </section>

    <section id="produk" class="py-16 px-6 bg-gray-100" data-aos="fade-up">
        <h2 class="text-3xl font-bold text-center mb-12">Produk Populer</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden" data-aos="zoom-in"
                data-aos-delay="150">
                <img src="../public/assets/tshirt-black.png" alt="Kaos Custom Desain"
                    class="w-full h-60 object-cover" />
                <div class="p-4">
                    <h3 class="text-lg font-semibold">Kaos Custom Desain</h3>
                    <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        <a href="{{ route('user.products') }}">
                            Lihat Detail
                        </a>
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden" data-aos="zoom-in"
                data-aos-delay="300">
                <img src="./public/assets/komunitas.jpg" alt="Produk Jadi" class="w-full h-60 object-cover" />
                <div class="p-4">
                    <h3 class="text-lg font-semibold">Produk Jadi</h3>
                    <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        <a href="/product">Lihat Detail</a>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-16 px-6 max-w-4xl mx-auto text-center" data-aos="fade-up">
        <h2 class="text-3xl font-bold mb-6">Tentang Sablon-App</h2>
        <p class="text-lg leading-relaxed mb-6">
            Sablon-App adalah platform yang menyediakan layanan sablon kaos berkualitas tinggi
            dengan berbagai pilihan desain dan bahan premium. Kami percaya setiap orang
            berhak memiliki kaos yang merepresentasikan gaya dan identitas mereka.
        </p>
        <a href="#fitur" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            Pelajari Lebih Lanjut
        </a>
    </section>

    <footer class="bg-gray-800 text-white py-6 text-center" data-aos="fade-up">
        <p>&copy; 2024 Sablon-App. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });

        // Mobile menu functionality
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuOpenIcon = document.getElementById('menu-icon-open');
            const menuCloseIcon = document.getElementById('menu-icon-close');

            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('animate-slide-down');
                menuOpenIcon.classList.add('hidden');
                menuCloseIcon.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('animate-slide-down');
                menuOpenIcon.classList.remove('hidden');
                menuCloseIcon.classList.add('hidden');
            }
        });

        // Close mobile menu when a link is clicked
        document.querySelectorAll('.mobile-link').forEach(link => {
            link.addEventListener('click', () => {
                const mobileMenu = document.getElementById('mobile-menu');
                const menuOpenIcon = document.getElementById('menu-icon-open');
                const menuCloseIcon = document.getElementById('menu-icon-close');

                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('animate-slide-down');
                menuOpenIcon.classList.remove('hidden');
                menuCloseIcon.classList.add('hidden');
            });
        });
    </script>

</body>

</html>
