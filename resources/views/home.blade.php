<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sablon-App | Solusi Sablon Custom Difabel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .animate-slide-down { animation: slide-down 0.3s ease-out forwards; }
        @keyframes slide-down { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .blue-gradient { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
    </style>
</head>

<body class="bg-white text-gray-800 scroll-smooth">

    <nav class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">
            <a href="#" class="text-2xl font-bold text-blue-600 tracking-tight">
                Sablon-<span class="text-gray-900">App</span>
            </a>

            <ul class="hidden md:flex gap-8 text-gray-700 font-medium">
                <li><a href="#fitur" class="hover:text-blue-600 transition">Fitur</a></li>
                <li><a href="#tentang" class="hover:text-blue-600 transition">Tentang Kami</a></li>
                <li><a href="#kontak" class="hover:text-blue-600 transition">Kontak</a></li>
            </ul>

            <div class="hidden md:flex gap-4">
                <a href="{{ route('login') }}" class="px-5 py-2 text-blue-600 font-semibold border border-blue-600 rounded-lg hover:bg-blue-50 transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition">Daftar</a>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 px-6 blue-gradient text-white overflow-hidden">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-1/2" data-aos="fade-right">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                    Ekspresikan Dirimu Melalui <span class="text-yellow-300">Desain Kustom</span>
                </h1>
                <p class="text-lg text-blue-100 mb-8 leading-relaxed">
                    Wujudkan ide desainmu menjadi produk berkualitas. Kami berdayakan kawan difabel untuk hasil sablon presisi dan penuh makna.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg">Mulai Desain</a>
                    <a href="#tentang" class="border border-white/50 px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="md:w-1/2 relative" data-aos="zoom-in">
                <img src="{{ asset('assets/sablon-logo.jpg') }}" alt="Sablon Banner" class="rounded-2xl shadow-2xl border-4 border-white/20 transform rotate-2">
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-gray-50 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Layanan Unggulan Kami</h2>
                <div class="w-20 h-1 bg-blue-600 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border-b-4 border-blue-600" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Custom Design</h3>
                    <p class="text-gray-600">Unggah desainmu sendiri atau gunakan editor online kami yang mudah digunakan.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border-b-4 border-blue-600" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Kualitas Premium</h3>
                    <p class="text-gray-600">Menggunakan teknik sablon modern (DTF/Manual) dengan bahan kaos katun 100% berkualitas.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border-b-4 border-blue-600" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Pemberdayaan Difabel</h3>
                    <p class="text-gray-600">Setiap pesanan Anda membantu menciptakan peluang kerja bagi kawan difabel di komunitas kami.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-24 px-6 bg-white">
        </section>

    <footer id="kontak" class="bg-gray-900 text-white pt-20 pb-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-12 mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-blue-400 mb-6">Sablon-App</h3>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Platform sablon kustom pertama yang memberdayakan kreativitas kawan difabel untuk menghasilkan karya berkualitas dunia.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#fitur" class="hover:text-white transition">Layanan Fitur</a></li>
                        <li><a href="https://difabeldaksatmg.com/" class="hover:text-white transition">Kisah Kami</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Mulai Desain</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Hubungi Kami</h4>
                    <p class="text-gray-400 mb-4"><i class="fas fa-map-marker-alt text-blue-400 mr-2"></i>Jl. Hayam Wuruk No.17, Brajan, Sidorejo, Kec. Temanggung, Kabupaten Temanggung, Jawa Tengah 56221</p>
                    <p class="text-gray-400 mb-4"><i class="fas fa-envelope text-blue-400 mr-2"></i> disabilitasdaksatmg@gmail.com</p>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-12 pb-8">
                <p class="text-center text-sm text-gray-500 uppercase tracking-widest mb-8">Didukung Oleh</p>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                    <img src="{{ asset('assets/unimma.png') }}" alt="UNIMMA" class="h-14">
                    <img src="{{ asset('assets/lppm-unimma-removebg-preview (1).png') }}" alt="LPPM UNIMMA" class="h-14">
                    <img src="{{ asset('assets/kddt.png') }}" alt="KDDT" class="h-16">
                    <img src="{{ asset('assets/tut-wuri-handayani.png') }}" alt="Tut Wuri Handayani" class="h-14">
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} Sablon-App. All rights reserved. Memberdayakan kreativitas tanpa batas.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>