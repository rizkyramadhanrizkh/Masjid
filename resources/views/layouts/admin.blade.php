<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin DKM') - Masjid Al-Kautsar Bogor</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #f1f5f9;
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col lg:flex-row">

    <!-- Mobile Top Header Bar -->
    <div class="lg:hidden bg-emerald-950 text-white px-4 py-3.5 flex items-center justify-between border-b border-emerald-800 sticky top-0 z-40">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 flex items-center justify-center text-amber-300">
                <i class="fa-solid fa-mosque text-sm"></i>
            </div>
            <span class="font-bold text-sm tracking-tight">DKM Al-Kautsar</span>
        </a>
        <button type="button" id="admin-sidebar-toggle" class="p-2 rounded-xl text-emerald-200 hover:bg-emerald-900 focus:outline-none">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Admin Sidebar (Desktop & Mobile Drawer) -->
    <aside id="admin-sidebar" class="hidden lg:flex flex-col w-72 bg-emerald-950 text-white min-h-screen border-r border-emerald-900 flex-shrink-0 z-50 fixed lg:static inset-y-0 left-0 transition-transform">
        <!-- Sidebar Brand -->
        <div class="p-6 border-b border-emerald-900/80 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 flex items-center justify-center text-amber-300 shadow-md">
                    <i class="fa-solid fa-mosque text-lg"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-base tracking-tight text-white">DKM Al-Kautsar</h2>
                    <p class="text-[11px] text-emerald-400 font-medium">Panel Pengurus &bull; Bogor</p>
                </div>
            </a>
            <button type="button" id="admin-sidebar-close" class="lg:hidden text-emerald-400 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Current User Role Badge -->
        <div class="px-6 py-4 border-b border-emerald-900/60 bg-emerald-900/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-800 text-amber-300 flex items-center justify-center text-sm font-bold flex-shrink-0">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ Auth::user()->role === 'super_admin' ? 'bg-amber-400 text-emerald-950' : (Auth::user()->role === 'bendahara' ? 'bg-blue-400 text-slate-950' : 'bg-emerald-700 text-white') }}">
                        {{ str_replace('_', ' ', Auth::user()->role ?? 'Admin') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                <i class="fa-solid fa-gauge w-5 text-center text-amber-400"></i>
                <span>Dashboard Ringkasan</span>
            </a>

            @if (Auth::user()->isAdminKonten())
                <div class="pt-4 pb-1 px-3 text-[11px] font-extrabold uppercase tracking-wider text-emerald-400/80">
                    Manajemen Konten
                </div>

                <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.news.*') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                    <i class="fa-solid fa-newspaper w-5 text-center text-emerald-400"></i>
                    <span>Berita & Pengumuman</span>
                </a>

                <a href="{{ route('admin.activities.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.activities.*') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center text-emerald-400"></i>
                    <span>Kegiatan & Kajian</span>
                </a>

                <a href="{{ route('admin.friday-schedules.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.friday-schedules.*') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-5 text-center text-emerald-400"></i>
                    <span>Jadwal Khutbah Jumat</span>
                </a>

                <a href="{{ route('admin.dkm-members.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dkm-members.*') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center text-emerald-400"></i>
                    <span>Pengurus DKM</span>
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-800 text-white font-bold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center text-amber-400"></i>
                    <span>Pengaturan Masjid & Iqamah</span>
                </a>
            @endif

            <div class="pt-4 pb-1 px-3 text-[11px] font-extrabold uppercase tracking-wider text-emerald-400/80">
                Pintasan
            </div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-emerald-200 hover:bg-emerald-900 hover:text-white transition">
                <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center text-emerald-400"></i>
                <span>Lihat Tampilan Jamaah</span>
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-emerald-900/80">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-900 hover:bg-red-800/80 text-emerald-200 hover:text-white text-xs font-bold transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar dari Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top App Bar (Desktop) -->
        <header class="hidden lg:flex items-center justify-between h-16 bg-white px-8 border-b border-slate-200 shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <h1 class="text-base font-extrabold text-slate-800">
                    @yield('header_title', 'Panel Kontrol DKM')
                </h1>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-globe text-emerald-600"></i> Buka Website
                </a>
                <div class="h-4 w-px bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-slate-600 font-semibold">{{ Auth::user()->email ?? '' }}</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-8 flex-grow">
            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            const closeBtn = document.getElementById('admin-sidebar-close');
            const sidebar = document.getElementById('admin-sidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                });
            }
            if (closeBtn && sidebar) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.add('hidden');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
