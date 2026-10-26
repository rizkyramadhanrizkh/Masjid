<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Panel Admin - {{ $setting->name ?? 'Masjid Al-Kautsar' }} Bogor</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .pattern-bg {
            background-color: #064e3b;
            background-image: radial-gradient(#059669 0.75px, transparent 0.75px), radial-gradient(#059669 0.75px, #064e3b 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
        }
    </style>
</head>
<body class="pattern-bg min-h-screen flex items-center justify-center p-4 selection:bg-amber-400 selection:text-emerald-950">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-emerald-800/20">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-amber-300 mx-auto flex items-center justify-center text-3xl shadow-lg mb-4">
                <i class="fa-solid fa-mosque"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Portal Pengurus DKM</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">{{ $setting->name ?? 'Masjid Al-Kautsar' }} Kota Bogor</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Pengurus</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email', 'konten@alkautsar.id') }}" required autofocus class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-medium">
                    <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi (Password)</label>
                <div class="relative">
                    <input type="password" id="password" name="password" value="password" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-medium">
                    <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat sesi saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm shadow-md hover:shadow-lg transition active:scale-98">
                Masuk ke Panel DKM &rarr;
            </button>
        </form>

        <!-- Quick Credentials Switcher (Convenient for Testing) -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center mb-2.5">
                Akun Demo Siap Pakai (1-Klik Isi):
            </p>
            <div class="grid grid-cols-3 gap-1.5 text-[11px] font-bold">
                <button type="button" onclick="fillCreds('konten@alkautsar.id', 'password')" class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">
                    Admin Konten
                </button>
                <button type="button" onclick="fillCreds('admin@alkautsar.id', 'password')" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-center">
                    Super Admin
                </button>
                <button type="button" onclick="fillCreds('bendahara@alkautsar.id', 'password')" class="p-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-center">
                    Bendahara
                </button>
            </div>
            <p class="text-[10px] text-slate-400 text-center mt-2">Password default: <span class="font-mono font-bold">password</span></p>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Jamaah
            </a>
        </div>
    </div>

    <script>
        function fillCreds(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
