<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masjid Al-Kautsar Bogor - Pusat Ibadah, Dakwah, dan Ukhuwah')</title>
    <meta name="description" content="@yield('meta_description', 'Website Resmi Masjid Al-Kautsar Bogor. Informasi jadwal sholat otomatis, agenda kajian, khutbah Jumat, berita, dan kegiatan DKM.')">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        arabic: ['"Amiri"', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 17px;
            color: #1f2937;
            background-color: #f8fafc;
        }
        .pattern-bg {
            background-color: #064e3b;
            background-image: radial-gradient(#059669 0.75px, transparent 0.75px), radial-gradient(#059669 0.75px, #064e3b 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
        }
        /* Elderly-friendly comfortable line-height and contrast */
        p, li {
            line-height: 1.75;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-emerald-600 selection:text-white">

    <!-- Top Announcement Bar / Live Clock -->
    <div class="bg-emerald-950 text-emerald-100 text-xs sm:text-sm py-2 px-4 border-b border-emerald-800/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-800/80 text-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Kota Bogor
                </span>
                <span class="hidden sm:inline text-emerald-200/70">|</span>
                <span id="live-datetime" class="font-medium text-emerald-100">Memuat waktu...</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <a href="{{ route('prayer.schedule') }}" class="hover:text-amber-300 transition flex items-center gap-1">
                    <i class="fa-regular fa-clock text-amber-400"></i> Waktu Sholat Otomatis Kemenag
                </a>
                <span class="text-emerald-800">|</span>
                <a href="{{ route('admin.login') }}" class="hover:text-amber-300 transition flex items-center gap-1 text-emerald-300">
                    <i class="fa-solid fa-user-shield"></i> Portal DKM
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo & Name -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 flex items-center justify-center text-white shadow-md shadow-emerald-700/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-mosque text-2xl text-amber-300"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-xl sm:text-2xl tracking-tight text-emerald-950 font-sans">
                                {{ $setting->name ?? 'Masjid Al-Kautsar' }}
                            </span>
                        </div>
                        <p class="text-xs text-emerald-700 font-medium tracking-wide">
                            {{ $setting->city ?? 'Bogor' }} &bull; Katulampa, Bogor Timur
                        </p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 font-semibold text-slate-700 text-sm">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Beranda
                    </a>
                    <a href="{{ route('prayer.schedule') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('prayer.schedule') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Jadwal Sholat
                    </a>
                    <a href="{{ route('activities.index') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('activities.*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Kegiatan & Kajian
                    </a>
                    <a href="{{ route('friday.schedule') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('friday.schedule') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Khutbah Jumat
                    </a>
                    <a href="{{ route('news.index') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('news.*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Berita
                    </a>
                    <a href="{{ route('profile') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('profile') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700' }}">
                        Profil Masjid
                    </a>
                </nav>

                <!-- Header CTA Button -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ route('donation') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-sm shadow-sm hover:shadow-md transition active:scale-95">
                        <i class="fa-solid fa-hand-holding-heart text-amber-300"></i>
                        <span>Infaq & Donasi</span>
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <button type="button" id="mobile-menu-btn" class="lg:hidden p-2.5 rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-6 shadow-xl">
            <div class="flex flex-col gap-1 text-base font-medium">
                <a href="{{ route('home') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-house w-6 text-emerald-600"></i> Beranda
                </a>
                <a href="{{ route('prayer.schedule') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('prayer.schedule') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-clock w-6 text-emerald-600"></i> Jadwal Sholat & Iqamah
                </a>
                <a href="{{ route('activities.index') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('activities.*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-calendar-days w-6 text-emerald-600"></i> Kegiatan & Kajian
                </a>
                <a href="{{ route('friday.schedule') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('friday.schedule') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-bullhorn w-6 text-emerald-600"></i> Jadwal Khutbah Jumat
                </a>
                <a href="{{ route('news.index') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('news.*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-newspaper w-6 text-emerald-600"></i> Berita & Pengumuman
                </a>
                <a href="{{ route('profile') }}" class="px-4 py-3 rounded-xl transition flex items-center gap-3 {{ request()->routeIs('profile') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-circle-info w-6 text-emerald-600"></i> Profil & DKM Masjid
                </a>
                <div class="pt-3 mt-2 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('donation') }}" class="w-full text-center py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-hand-holding-heart text-amber-300"></i> Infaq & Donasi Masjid
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}?text={{ urlencode('Assalamu\'alaikum Pengurus DKM Masjid Al-Kautsar Bogor, saya ingin bertanya:') }}" target="_blank" class="w-full text-center py-2.5 rounded-xl bg-emerald-50 text-emerald-800 font-semibold flex items-center justify-center gap-2 border border-emerald-200">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-lg"></i> Hubungi WhatsApp DKM
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-emerald-950 text-emerald-100 pt-16 pb-24 lg:pb-12 border-t-4 border-amber-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Col 1: Masjid Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-800 flex items-center justify-center text-white border border-emerald-600 shadow-md">
                            <i class="fa-solid fa-mosque text-2xl text-amber-300"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ $setting->name ?? 'Masjid Al-Kautsar' }}</h3>
                            <p class="text-xs text-emerald-300">{{ $setting->tagline ?? 'Pusat Ibadah, Dakwah, dan Ukhuwah Kota Bogor' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-emerald-200/80 leading-relaxed mb-6 max-w-md">
                        Melayani jamaah Kota Bogor dengan menghadirkan suasana ibadah yang khusyuk, pengajian ilmiah Ahlus Sunnah wal Jama'ah, serta kepedulian sosial bagi kemaslahatan umat.
                    </p>
                    <div class="space-y-2.5 text-sm text-emerald-200">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-amber-400"></i>
                            <span>{{ $setting->address ?? 'Jl. Danau Bogor Raya, Katulampa, Bogor Timur, Kota Bogor 16143' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}" target="_blank" class="hover:text-white underline decoration-emerald-700">
                                {{ $setting->whatsapp ?? '0812-3456-7890' }} (Layanan Jamaah)
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-amber-400"></i>
                            <span>{{ $setting->email ?? 'kontak@masjidalkautsar-bogor.id' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Tautan Halaman -->
                <div>
                    <h4 class="text-base font-bold text-white uppercase tracking-wider mb-4 border-b border-emerald-800/80 pb-2">
                        Tautan Cepat
                    </h4>
                    <ul class="space-y-2.5 text-sm text-emerald-200">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Beranda Utama</a></li>
                        <li><a href="{{ route('prayer.schedule') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Jadwal Sholat Bogor</a></li>
                        <li><a href="{{ route('activities.index') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Agenda & Kajian</a></li>
                        <li><a href="{{ route('friday.schedule') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Khutbah Jumat</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Berita & Pengumuman</a></li>
                        <li><a href="{{ route('profile') }}" class="hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-emerald-500"></i> Profil & Pengurus DKM</a></li>
                    </ul>
                </div>

                <!-- Col 3: Donasi & Rekening -->
                <div>
                    <h4 class="text-base font-bold text-white uppercase tracking-wider mb-4 border-b border-emerald-800/80 pb-2">
                        Infaq & Sedekah
                    </h4>
                    <div class="bg-emerald-900/60 p-4 rounded-2xl border border-emerald-800/80 text-sm">
                        <p class="text-xs text-amber-300 font-semibold mb-1 uppercase tracking-wider">{{ $setting->bank_name ?? 'Bank Syariah Indonesia (BSI)' }}</p>
                        <p class="text-xl font-extrabold text-white font-mono tracking-wider">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                        <p class="text-xs text-emerald-300 mt-1">a.n {{ $setting->bank_account_holder ?? 'DKM Masjid Al-Kautsar Bogor' }}</p>
                        <div class="mt-4">
                            <a href="{{ route('donation') }}" class="inline-block w-full text-center py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-emerald-950 font-bold text-xs transition shadow-sm">
                                Detail Rekening & Program
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Admin link -->
            <div class="mt-12 pt-6 border-t border-emerald-800/60 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-emerald-300">
                <p>&copy; {{ date('Y') }} DKM {{ $setting->name ?? 'Masjid Al-Kautsar' }} Bogor. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile') }}" class="hover:text-white">Tentang DKM</a>
                    <span>&bull;</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-amber-300 font-medium">Panel Pengurus / Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom App Bar (Optimized for Smartphone Jamaah) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 py-1.5 px-3 shadow-2xl">
        <div class="grid grid-cols-5 gap-1 text-center text-xs font-semibold">
            <a href="{{ route('home') }}" class="py-1.5 rounded-xl flex flex-col items-center {{ request()->routeIs('home') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600' }}">
                <i class="fa-solid fa-house text-lg mb-0.5"></i>
                <span class="text-[11px]">Beranda</span>
            </a>
            <a href="{{ route('prayer.schedule') }}" class="py-1.5 rounded-xl flex flex-col items-center {{ request()->routeIs('prayer.schedule') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600' }}">
                <i class="fa-solid fa-clock text-lg mb-0.5"></i>
                <span class="text-[11px]">Sholat</span>
            </a>
            <a href="{{ route('activities.index') }}" class="py-1.5 rounded-xl flex flex-col items-center {{ request()->routeIs('activities.*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600' }}">
                <i class="fa-solid fa-calendar-days text-lg mb-0.5"></i>
                <span class="text-[11px]">Kajian</span>
            </a>
            <a href="{{ route('news.index') }}" class="py-1.5 rounded-xl flex flex-col items-center {{ request()->routeIs('news.*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600' }}">
                <i class="fa-solid fa-newspaper text-lg mb-0.5"></i>
                <span class="text-[11px]">Berita</span>
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}" target="_blank" class="py-1.5 rounded-xl flex flex-col items-center text-emerald-600 font-bold">
                <i class="fa-brands fa-whatsapp text-lg mb-0.5 text-emerald-500"></i>
                <span class="text-[11px]">Kontak</span>
            </a>
        </div>
    </nav>

    <!-- JavaScript: Mobile Menu Toggle & Live Clock -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Live Date & Time Clock
            function updateClock() {
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                const formatter = new Intl.DateTimeFormat('id-ID', options);
                const el = document.getElementById('live-datetime');
                if (el) {
                    el.textContent = formatter.format(now) + ' WIB';
                }
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    @stack('scripts')
</body>
</html>
