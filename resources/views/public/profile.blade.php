@extends('layouts.public')

@section('title', 'Profil & Struktur DKM - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Sejarah pendirian, visi misi, struktur organisasi DKM, serta peta lokasi Masjid Al-Kautsar Kota Bogor.')

@section('content')
@php
    // ---- Lokasi & kontak ----
    $mapsUrl   = 'https://www.google.com/maps/place/Masjid+Al-+Kautsar/@-6.603909,106.8263035,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69c672884c198d:0xc91f6dcc247555fd!8m2!3d-6.6039143!4d106.8288784!16s%2Fg%2F11b6s50xy1?authuser=0&entry=ttu';
    $mapsEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1981!2d106.8288784!3d-6.6039143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c672884c198d%3A0xc91f6dcc247555fd!2sMasjid%20Al-%20Kautsar!5e0!3m2!1sid!2sid!4v1761449000000!5m2!1sid!2sid';

    $waRaw    = $setting->whatsapp ?? '081234567890';
    $waNumber = preg_replace('/[^0-9]/', '', $waRaw);
    if (substr($waNumber, 0, 1) === '0') {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waLink = 'https://wa.me/' . $waNumber . '?text=' . urlencode("Assalamu'alaikum Pengurus DKM Masjid Al-Kautsar Bogor,");

    // ---- Misi: pecah per baris, buang penomoran/bullet bawaan ----
    $missions = [];
    $missionText = str_replace("\r", '', (string) ($setting->mission ?? ''));
    foreach (explode("\n", $missionText) as $line) {
        $line = trim($line);
        $line = preg_replace('/^[0-9]+[.]\s*/', '', $line);
        $line = ltrim($line, '-* ');
        if ($line !== '') {
            $missions[] = $line;
        }
    }

    // ---- Statistik ----
    $stats = [
        ['icon' => 'fa-calendar-check', 'value' => 2008,  'suffix' => '',     'label' => 'Tahun Berdiri',     'count' => false],
        ['icon' => 'fa-vector-square',  'value' => 2500,  'suffix' => ' m²',  'label' => 'Luas Tanah Wakaf',  'count' => true],
        ['icon' => 'fa-people-roof',    'value' => 1500,  'suffix' => '',     'label' => 'Kapasitas Jamaah',  'count' => true],
        ['icon' => 'fa-location-dot',   'value' => 'Bogor Timur', 'suffix' => '', 'label' => 'Kota Bogor',    'count' => false],
    ];
@endphp

<style>
    html { scroll-behavior: smooth; }
    section[id] { scroll-margin-top: 96px; }

    /* ---------- Reveal saat scroll ---------- */
    .reveal { opacity: 0; transform: translateY(28px) scale(.98); transition: opacity .8s cubic-bezier(.2,.7,.2,1), transform .8s cubic-bezier(.2,.7,.2,1); }
    .reveal.is-visible { opacity: 1; transform: none; }
    .stagger > * { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s cubic-bezier(.2,.7,.2,1); }
    .stagger.is-visible > * { opacity: 1; transform: none; }
    .stagger.is-visible > *:nth-child(1) { transition-delay: .05s; }
    .stagger.is-visible > *:nth-child(2) { transition-delay: .12s; }
    .stagger.is-visible > *:nth-child(3) { transition-delay: .19s; }
    .stagger.is-visible > *:nth-child(4) { transition-delay: .26s; }
    .stagger.is-visible > *:nth-child(5) { transition-delay: .33s; }
    .stagger.is-visible > *:nth-child(6) { transition-delay: .40s; }
    .stagger.is-visible > *:nth-child(7) { transition-delay: .47s; }
    .stagger.is-visible > *:nth-child(8) { transition-delay: .54s; }

    /* ---------- Utilitas ---------- */
    .glass { background: rgba(255,255,255,.12); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* ---------- Hero ---------- */
    @keyframes floaty { 0%,100% { transform: translateY(0) } 50% { transform: translateY(-18px) } }
    @keyframes drift  { 0%,100% { transform: translate(0,0) scale(1) } 50% { transform: translate(24px,-20px) scale(1.08) } }
    @keyframes shine  { 0% { background-position: 0% 50% } 100% { background-position: 200% 50% } }
    @keyframes pulse-ring { 0% { transform: scale(1); opacity: .6 } 100% { transform: scale(1.9); opacity: 0 } }
    .blob-a { animation: drift 12s ease-in-out infinite; }
    .blob-b { animation: drift 15s ease-in-out infinite reverse; }
    .float-a { animation: floaty 7s ease-in-out infinite; }
    .float-b { animation: floaty 9s ease-in-out infinite .8s; }
    .text-shine {
        background: linear-gradient(90deg, #fcd34d, #fef3c7, #fbbf24, #fef3c7, #fcd34d);
        background-size: 200% auto;
        -webkit-background-clip: text; background-clip: text; color: transparent;
        animation: shine 5s linear infinite;
    }
    .wa-pulse::before { content: ''; position: absolute; inset: 0; border-radius: 9999px; background: #10b981; animation: pulse-ring 2s ease-out infinite; z-index: -1; }

    /* ---------- Tombol: efek kilap ---------- */
    .btn-shine { position: relative; overflow: hidden; }
    .btn-shine::after {
        content: ''; position: absolute; top: 0; left: -75%; width: 50%; height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.55), transparent);
        transform: skewX(-20deg); transition: left .7s ease;
    }
    .btn-shine:hover::after { left: 130%; }

    /* ---------- Nav chip ---------- */
    .nav-chip { transition: all .25s ease; }
    .nav-chip.active { background: linear-gradient(135deg,#059669,#047857); color: #fff; border-color: transparent; box-shadow: 0 8px 20px -6px rgba(5,150,105,.55); }
    #scrollProgress { transform-origin: left; transform: scaleX(0); }

    /* ---------- Kartu umum ---------- */
    .card-lift { transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s ease, border-color .35s ease; }
    .card-lift:hover { transform: translateY(-8px); box-shadow: 0 30px 50px -20px rgba(6,78,59,.35); border-color: #a7f3d0; }
    .icon-spin { transition: transform .5s cubic-bezier(.2,.7,.2,1); }
    .card-lift:hover .icon-spin { transform: rotate(-8deg) scale(1.15); }

    /* Garis gradien bawah kartu statistik */
    .stat-bar { position: relative; }
    .stat-bar::after { content: ''; position: absolute; left: 1.5rem; right: 1.5rem; bottom: 0; height: 3px; border-radius: 9999px; background: linear-gradient(90deg,#10b981,#fbbf24); transform: scaleX(0); transform-origin: left; transition: transform .45s ease; }
    .stat-bar:hover::after { transform: scaleX(1); }

    /* ---------- Timeline sejarah ---------- */
    .history-text { position: relative; }
    .history-text::first-letter { font-size: 3.4rem; font-weight: 900; color: #047857; float: left; line-height: .9; padding-right: .5rem; padding-top: .2rem; }

    /* ---------- Misi ---------- */
    .mission-item { transition: transform .3s ease, background .3s ease, box-shadow .3s ease; }
    .mission-item:hover { transform: translateX(8px); background: #ecfdf5; box-shadow: 0 10px 25px -12px rgba(5,150,105,.4); }
    .mission-item:hover .mission-num { background: linear-gradient(135deg,#10b981,#047857); color: #fff; transform: rotate(-6deg) scale(1.1); }
    .mission-num { transition: all .3s ease; }

    /* ---------- Anggota DKM ---------- */
    .member-card .ring-avatar { transition: transform .5s cubic-bezier(.2,.7,.2,1); }
    .member-card:hover .ring-avatar { transform: scale(1.08) rotate(4deg); }
    .member-card .glow { opacity: 0; transition: opacity .4s ease; }
    .member-card:hover .glow { opacity: 1; }
    .member-card img { transition: transform .6s ease; }
    .member-card:hover img { transform: scale(1.12); }

    /* ---------- Info kontak ---------- */
    .contact-card { transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease; }
    .contact-card:hover { transform: translateX(6px); border-color: #6ee7b7; box-shadow: 0 18px 35px -18px rgba(5,150,105,.45); }
    .contact-card .c-icon { transition: all .3s ease; }
    .contact-card:hover .c-icon { background: linear-gradient(135deg,#10b981,#047857); color: #fff; transform: rotate(-8deg) scale(1.08); }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .stagger > * { opacity: 1 !important; transform: none !important; transition: none !important; }
        .blob-a, .blob-b, .float-a, .float-b, .text-shine, .wa-pulse::before { animation: none !important; }
    }
</style>

<!-- Progress scroll -->
<div class="fixed top-0 left-0 right-0 h-1 z-[60] pointer-events-none">
    <div id="scrollProgress" class="h-full bg-gradient-to-r from-emerald-400 via-amber-300 to-emerald-500"></div>
</div>

<!-- ================= HERO + STATISTIK ================= -->
<div class="relative bg-slate-50">
    <div class="relative overflow-hidden pattern-bg text-white">
        <div class="blob-a absolute -top-24 -right-24 w-[26rem] h-[26rem] rounded-full bg-amber-400/25 blur-3xl"></div>
        <div class="blob-b absolute -bottom-32 -left-24 w-[26rem] h-[26rem] rounded-full bg-emerald-300/25 blur-3xl"></div>

        <!-- Ikon melayang dekoratif -->
        <i class="fa-solid fa-moon float-a absolute top-24 left-[8%] text-5xl text-amber-300/20 hidden md:block"></i>
        <i class="fa-solid fa-star float-b absolute top-40 right-[10%] text-3xl text-white/20 hidden md:block"></i>
        <i class="fa-solid fa-star-and-crescent float-b absolute bottom-40 left-[14%] text-4xl text-white/10 hidden md:block"></i>
        <i class="fa-solid fa-mosque float-a absolute bottom-36 right-[8%] text-6xl text-white/10 hidden md:block"></i>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-48 sm:pt-24 sm:pb-56 text-center">
            <span class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest glass text-amber-300 border border-white/20 mb-6 hover:bg-white/20 hover:scale-105 transition">
                <i class="fa-solid fa-mosque"></i> Mengenal Lebih Dekat
            </span>
            <h1 class="reveal text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-[1.08]">
                Profil &amp; Struktur
                <span class="block text-shine mt-1">DKM Masjid Al-Kautsar</span>
            </h1>
            <p class="reveal text-base sm:text-xl text-emerald-50/90 mt-6 max-w-2xl mx-auto leading-relaxed">
                {{ $setting->tagline ?? 'Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor' }}
            </p>

            <div class="reveal mt-9 flex flex-wrap justify-center gap-3">
                <a href="{{ $waLink }}" target="_blank" rel="noopener"
                   class="btn-shine group px-7 py-3.5 rounded-2xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-bold text-sm shadow-xl shadow-black/25 flex items-center gap-2 transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-400/30 active:scale-95">
                    <i class="fa-brands fa-whatsapp text-lg group-hover:rotate-12 group-hover:scale-125 transition"></i> Hubungi Pengurus
                </a>
                <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
                   class="btn-shine group px-7 py-3.5 rounded-2xl glass border border-white/25 hover:bg-white/25 text-white font-bold text-sm flex items-center gap-2 transition duration-300 hover:-translate-y-1 active:scale-95">
                    <i class="fa-solid fa-diamond-turn-right group-hover:translate-x-1 transition"></i> Petunjuk Arah
                </a>
            </div>
        </div>

        <!-- Lengkung dekoratif -->
        <svg class="absolute bottom-0 left-0 w-full text-slate-50" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" aria-hidden="true" style="height:60px">
            <path d="M0,60 L0,30 Q360,-10 720,25 T1440,20 L1440,60 Z"></path>
        </svg>
    </div>

    <!-- Statistik: menumpang di bawah hero (tidak lagi tertutup nav) -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-36 sm:-mt-40 z-10">
        <section class="stagger grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5" aria-label="Fakta singkat masjid">
            @foreach ($stats as $stat)
                <div class="card-lift stat-bar group bg-white/95 backdrop-blur rounded-3xl p-5 sm:p-7 shadow-xl shadow-emerald-900/10 border border-slate-100 cursor-default">
                    <div class="icon-spin w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center mb-4 shadow-lg shadow-emerald-600/30">
                        <i class="fa-solid {{ $stat['icon'] }}"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-none tracking-tight">
                        @if ($stat['count'])
                            <span data-count="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] }}">0{{ $stat['suffix'] }}</span>
                        @else
                            {{ $stat['value'] }}{{ $stat['suffix'] }}
                        @endif
                    </p>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-2 group-hover:text-emerald-700 transition">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </section>
    </div>
</div>

<!-- ================= NAVIGASI ANTAR BAGIAN ================= -->
<div class="sticky top-0 z-30 bg-slate-50/85 backdrop-blur-xl border-b border-slate-200/70 mt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex gap-2 sm:justify-center overflow-x-auto py-3 no-scrollbar" aria-label="Navigasi halaman profil">
            <a href="#sejarah"   class="nav-chip whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-emerald-500 hover:text-emerald-700 hover:-translate-y-0.5 hover:shadow-md"><i class="fa-solid fa-landmark mr-1.5"></i>Sejarah</a>
            <a href="#visi-misi" class="nav-chip whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-emerald-500 hover:text-emerald-700 hover:-translate-y-0.5 hover:shadow-md"><i class="fa-regular fa-compass mr-1.5"></i>Visi &amp; Misi</a>
            <a href="#struktur"  class="nav-chip whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-emerald-500 hover:text-emerald-700 hover:-translate-y-0.5 hover:shadow-md"><i class="fa-solid fa-sitemap mr-1.5"></i>Struktur DKM</a>
            <a href="#lokasi"    class="nav-chip whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-emerald-500 hover:text-emerald-700 hover:-translate-y-0.5 hover:shadow-md"><i class="fa-solid fa-map-pin mr-1.5"></i>Lokasi</a>
        </nav>
    </div>
</div>

<div class="bg-slate-50 relative overflow-hidden">
    <!-- Dekorasi latar -->
    <div class="absolute top-[30%] -left-40 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl pointer-events-none"></div>
    <div class="absolute top-[65%] -right-40 w-96 h-96 rounded-full bg-amber-200/30 blur-3xl pointer-events-none"></div>

<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 space-y-24 sm:space-y-32">

    <!-- ================= SEJARAH ================= -->
    <section id="sejarah" class="reveal">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            <div class="lg:col-span-4 lg:sticky lg:top-28">
                <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-clock-rotate-left"></i> Perjalanan &amp; Sejarah
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-4 leading-tight">
                    Sejarah Pendirian <span class="text-emerald-700">Masjid Al-Kautsar</span>
                </h2>
                <div class="w-16 h-1.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-200 mt-5"></div>
                <p class="text-slate-500 mt-5 text-sm sm:text-base leading-relaxed">
                    Dari tanah wakaf hingga menjadi pusat kegiatan jamaah, berikut kisah singkat perjalanan kami.
                </p>
            </div>

            <div class="card-lift lg:col-span-8 relative bg-white rounded-[2rem] p-6 sm:p-10 border border-slate-100 shadow-xl shadow-emerald-900/5 overflow-hidden">
                <i class="fa-solid fa-landmark absolute -bottom-6 -right-4 text-[9rem] text-emerald-50 pointer-events-none"></i>
                <div class="relative pl-7 border-l-2 border-dashed border-emerald-200">
                    <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
                    <div class="history-text text-slate-700 leading-loose text-base sm:text-lg space-y-4">
                        {!! nl2br(e($setting->history ?? 'Sejarah masjid belum diisi oleh pengurus.')) !!}
                    </div>
                    <span class="absolute -left-[9px] bottom-1 w-4 h-4 rounded-full bg-amber-400 ring-4 ring-amber-100"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= VISI & MISI ================= -->
    <section id="visi-misi" class="reveal">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full">
                <i class="fa-regular fa-compass"></i> Arah &amp; Langkah
            </span>
            <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-4">Visi &amp; Misi</h2>
            <div class="w-16 h-1.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-200 mt-5 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Visi -->
            <div class="card-lift group lg:col-span-2 relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-800 to-emerald-950 text-white rounded-[2rem] p-8 sm:p-10 shadow-2xl shadow-emerald-900/25 flex flex-col justify-between">
                <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-amber-300/10 blur-2xl group-hover:scale-150 transition duration-700"></div>
                <i class="fa-solid fa-quote-right absolute top-2 right-5 text-8xl text-white/5 group-hover:text-white/10 group-hover:rotate-6 transition duration-500"></i>
                <div class="relative">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-amber-300 mb-6">
                        <i class="fa-regular fa-compass"></i> Visi
                    </span>
                    <p class="text-xl sm:text-2xl font-bold leading-relaxed">
                        “{{ $setting->vision ?? 'Visi masjid belum diisi.' }}”
                    </p>
                </div>
                <div class="relative mt-8 pt-5 border-t border-white/15 text-xs text-emerald-200 flex items-center gap-2">
                    <i class="fa-solid fa-check-double text-amber-300"></i> Berlandaskan Al-Qur'an dan As-Sunnah
                </div>
            </div>

            <!-- Misi -->
            <div class="lg:col-span-3 bg-white rounded-[2rem] p-6 sm:p-10 shadow-xl shadow-emerald-900/5 border border-slate-100">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 mb-5">
                    <i class="fa-solid fa-list-check"></i> Misi Pengurus DKM
                </span>
                @if (count($missions) > 0)
                    <ol class="space-y-2">
                        @foreach ($missions as $i => $mission)
                            <li class="mission-item flex gap-4 items-start rounded-2xl p-3 cursor-default">
                                <span class="mission-num flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 font-black flex items-center justify-center">
                                    {{ $i + 1 }}
                                </span>
                                <p class="text-slate-700 leading-relaxed pt-1.5">{{ $mission }}</p>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-slate-400">Misi belum diisi oleh pengurus.</p>
                @endif
                <div class="mt-6 pt-5 border-t border-slate-100 text-xs text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-users text-emerald-600"></i> Khidmah untuk Jamaah &amp; Umat
                </div>
            </div>
        </div>
    </section>

    <!-- ================= STRUKTUR DKM ================= -->
    <section id="struktur" class="reveal">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full">
                <i class="fa-solid fa-hand-holding-heart"></i> Amanah &amp; Pengabdian
            </span>
            <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-4">Struktur Pengurus DKM</h2>
            <div class="w-16 h-1.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-200 mt-5 mx-auto"></div>
            <p class="text-slate-500 mt-4">Dewan Kemakmuran Masjid (DKM) Periode 2024 - 2029</p>
        </div>

        <div class="stagger grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($members as $member)
                <article class="member-card card-lift group relative bg-white rounded-[1.75rem] p-6 pt-8 text-center border border-slate-100 shadow-lg shadow-emerald-900/5 overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-emerald-50 to-transparent"></div>
                    <div class="glow absolute -top-10 left-1/2 -translate-x-1/2 w-40 h-40 rounded-full bg-amber-300/30 blur-2xl"></div>

                    <div class="ring-avatar relative w-28 h-28 mx-auto rounded-full p-1 bg-gradient-to-br from-emerald-400 via-emerald-500 to-amber-300 shadow-lg shadow-emerald-600/20">
                        <div class="w-full h-full rounded-full overflow-hidden bg-emerald-100 flex items-center justify-center border-[3px] border-white">
                            @if ($member->photo)
                                <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl font-black text-emerald-700">{{ strtoupper(mb_substr($member->name, 0, 1)) }}</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="relative mt-5 text-base font-extrabold text-slate-900 group-hover:text-emerald-800 transition">
                        {{ $member->name }}
                    </h3>
                    <p class="relative text-sm font-bold text-emerald-700 mt-1">{{ $member->position }}</p>

                    <span class="relative inline-block mt-3 text-[11px] font-semibold text-slate-500 bg-slate-50 px-3 py-1 rounded-full border border-slate-200 group-hover:bg-emerald-50 group-hover:border-emerald-200 group-hover:text-emerald-700 transition">
                        {{ $member->period }}
                    </span>

                    @if ($member->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $member->phone) }}"
                           class="relative mt-4 flex items-center justify-center gap-2 text-xs font-semibold text-slate-500 hover:text-white hover:bg-emerald-600 rounded-full py-2 px-4 bg-slate-50 transition duration-300 hover:scale-105">
                            <i class="fa-solid fa-phone text-[10px]"></i> {{ $member->phone }}
                        </a>
                    @endif
                </article>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-dashed border-slate-300 text-slate-400">
                    <i class="fa-solid fa-user-group text-4xl mb-3 block"></i>
                    Belum ada data pengurus yang dicatat.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ================= LOKASI & KONTAK ================= -->
    <section id="lokasi" class="reveal">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100/70 px-3 py-1 rounded-full">
                <i class="fa-solid fa-map-location-dot"></i> Kunjungi Kami
            </span>
            <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-4">Lokasi &amp; Akses Jamaah</h2>
            <div class="w-16 h-1.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-200 mt-5 mx-auto"></div>
            <p class="text-slate-500 mt-4">
                Berlokasi strategis di Bogor Timur, mudah dijangkau dari Tol Jagorawi (exit Baranangsiang / Tol Bogor).
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Kartu kontak -->
            <div class="lg:col-span-2 space-y-4">
                <div class="contact-card bg-white rounded-3xl p-5 border border-slate-100 shadow-lg shadow-emerald-900/5 flex gap-4">
                    <div class="c-icon w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">Alamat Lengkap</p>
                        <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                            {{ $setting->address ?? 'Jl. Danau Bogor Raya, Katulampa, Kec. Bogor Timur, Kota Bogor, Jawa Barat 16143' }}
                        </p>
                    </div>
                </div>

                <a href="{{ $waLink }}" target="_blank" rel="noopener"
                   class="contact-card bg-white rounded-3xl p-5 border border-slate-100 shadow-lg shadow-emerald-900/5 flex gap-4">
                    <div class="c-icon w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-brands fa-whatsapp text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">Layanan WhatsApp DKM</p>
                        <p class="text-emerald-700 font-bold text-sm mt-1">{{ $waRaw }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">Ketuk untuk memulai chat</p>
                    </div>
                </a>

                <div class="contact-card bg-white rounded-3xl p-5 border border-slate-100 shadow-lg shadow-emerald-900/5 flex gap-4">
                    <div class="c-icon w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <i class="fa-regular fa-envelope text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900">Email Resmi</p>
                        <p class="text-slate-500 text-sm mt-1 break-all">{{ $setting->email ?? 'kontak@masjidalkautsar-bogor.id' }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-1">
                    <a href="{{ $waLink }}" target="_blank" rel="noopener"
                       class="btn-shine group flex-1 px-5 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition duration-300 hover:-translate-y-1 active:scale-95">
                        <i class="fa-brands fa-whatsapp text-lg group-hover:rotate-12 transition"></i> Chat Pengurus
                    </a>
                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
                       class="group flex-1 px-5 py-3.5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50 text-slate-700 font-bold text-sm flex items-center justify-center gap-2 transition duration-300 hover:-translate-y-1 active:scale-95">
                        <i class="fa-solid fa-diamond-turn-right text-emerald-600 group-hover:translate-x-1 transition"></i> Buka Google Maps
                    </a>
                </div>
            </div>

            <!-- Peta -->
            <div class="group lg:col-span-3 relative h-[420px] lg:h-auto lg:min-h-[480px] rounded-[2rem] overflow-hidden shadow-2xl shadow-emerald-900/15 border-4 border-white ring-1 ring-slate-200 hover:shadow-emerald-900/30 transition duration-500">
                <iframe src="{{ $mapsEmbed }}"
                        class="absolute inset-0 w-full h-full border-0 grayscale-[30%] group-hover:grayscale-0 transition duration-700"
                        allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Masjid Al-Kautsar Bogor"></iframe>

                <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
                   class="absolute left-4 bottom-4 right-4 sm:right-auto sm:max-w-xs bg-white/95 backdrop-blur rounded-2xl p-4 shadow-xl flex items-center gap-3 hover:bg-white hover:-translate-y-1 hover:shadow-2xl transition duration-300">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-mosque"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-extrabold text-slate-900 text-sm leading-tight">Masjid Al-Kautsar</p>
                        <p class="text-xs text-emerald-700 font-semibold">Lihat di Google Maps <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></p>
                    </div>
                </a>
            </div>
        </div>
    </section>
</div>
</div>

<!-- Tombol WhatsApp melayang -->
<a href="{{ $waLink }}" target="_blank" rel="noopener" aria-label="Chat WhatsApp pengurus DKM"
   class="wa-pulse fixed bottom-5 right-5 z-40 w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-2xl shadow-emerald-700/40 hover:scale-110 hover:-rotate-6 transition duration-300">
    <i class="fa-brands fa-whatsapp text-3xl"></i>
</a>

<!-- Tombol kembali ke atas -->
<button id="toTop" type="button" aria-label="Kembali ke atas"
        class="fixed bottom-24 right-6 z-40 w-11 h-11 rounded-full bg-white text-emerald-700 border border-slate-200 shadow-lg flex items-center justify-center opacity-0 pointer-events-none translate-y-4 transition duration-300 hover:bg-emerald-600 hover:text-white hover:-translate-y-1">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
    (function () {
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Animasi muncul saat di-scroll
        var items = document.querySelectorAll('.reveal, .stagger');
        if ('IntersectionObserver' in window && !reduce) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
                });
            }, { threshold: 0.12 });
            items.forEach(function (el) { io.observe(el); });
        } else {
            items.forEach(function (el) { el.classList.add('is-visible'); });
        }

        // Hitung angka statistik
        var counters = document.querySelectorAll('[data-count]');
        function runCounter(el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            var suffix = el.getAttribute('data-suffix') || '';
            if (reduce) { el.textContent = target.toLocaleString('id-ID') + suffix; return; }
            var start = null, dur = 1600;
            function step(ts) {
                if (!start) start = ts;
                var p = Math.min((ts - start) / dur, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.floor(eased * target).toLocaleString('id-ID') + suffix;
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }
        if ('IntersectionObserver' in window) {
            var co = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { runCounter(e.target); co.unobserve(e.target); }
                });
            }, { threshold: 0.6 });
            counters.forEach(function (c) { co.observe(c); });
        } else {
            counters.forEach(runCounter);
        }

        // Tandai chip navigasi sesuai bagian yang sedang dilihat
        var chips = document.querySelectorAll('.nav-chip');
        var sections = Array.prototype.map.call(chips, function (c) {
            return document.querySelector(c.getAttribute('href'));
        });
        if ('IntersectionObserver' in window) {
            var spy = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        chips.forEach(function (c) {
                            c.classList.toggle('active', c.getAttribute('href') === '#' + e.target.id);
                        });
                    }
                });
            }, { rootMargin: '-40% 0px -55% 0px' });
            sections.forEach(function (s) { if (s) spy.observe(s); });
        }

        // Progress scroll + tombol ke atas
        var bar = document.getElementById('scrollProgress');
        var topBtn = document.getElementById('toTop');
        function onScroll() {
            var h = document.documentElement;
            var max = h.scrollHeight - h.clientHeight;
            var pct = max > 0 ? h.scrollTop / max : 0;
            bar.style.transform = 'scaleX(' + pct + ')';
            var show = h.scrollTop > 500;
            topBtn.classList.toggle('opacity-0', !show);
            topBtn.classList.toggle('pointer-events-none', !show);
            topBtn.classList.toggle('translate-y-4', !show);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
        topBtn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    })();
</script>
@endsection