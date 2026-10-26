@extends('layouts.public')

@section('title', 'Beranda - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')

@section('content')
<style>
    html { scroll-behavior: smooth; }

    /* ---------- Scroll reveal ---------- */
    .reveal-ready .reveal {
        opacity: 0;
        transform: translateY(28px) scale(.98);
        transition: opacity .8s cubic-bezier(.22,1,.36,1), transform .8s cubic-bezier(.22,1,.36,1);
        transition-delay: var(--d, 0ms);
        will-change: opacity, transform;
    }
    .reveal-ready .reveal.reveal-left  { transform: translateX(-32px); }
    .reveal-ready .reveal.reveal-right { transform: translateX(32px); }
    .reveal-ready .reveal.is-visible   { opacity: 1; transform: none; }

    /* ---------- Hero entrance ---------- */
    @@keyframes fadeUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
    .hero-in { opacity: 0; animation: fadeUp .9s cubic-bezier(.22,1,.36,1) forwards; animation-delay: var(--d, 0ms); }

    /* ---------- Floating blobs & stars ---------- */
    @@keyframes floaty { 0%,100% { transform: translate3d(0,0,0) scale(1); } 50% { transform: translate3d(20px,-28px,0) scale(1.08); } }
    @@keyframes floaty2 { 0%,100% { transform: translate3d(0,0,0); } 50% { transform: translate3d(-26px,22px,0); } }
    .blob-a { animation: floaty 12s ease-in-out infinite; }
    .blob-b { animation: floaty2 15s ease-in-out infinite; }
    @@keyframes twinkle { 0%,100% { opacity: .15; transform: scale(.8); } 50% { opacity: .9; transform: scale(1.2); } }
    .star { position: absolute; color: #fcd34d; animation: twinkle 3.5s ease-in-out infinite; animation-delay: var(--d, 0s); pointer-events: none; }

    /* ---------- Shimmering gold text ---------- */
    @@keyframes shimmer { to { background-position: 200% center; } }
    .text-shimmer {
        background: linear-gradient(90deg, #fcd34d, #fffbeb, #fbbf24, #fcd34d);
        background-size: 200% auto;
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
        animation: shimmer 5s linear infinite;
    }

    /* ---------- Prayer cards ---------- */
    .prayer-scroll { scrollbar-width: none; -webkit-overflow-scrolling: touch; }
    .prayer-scroll::-webkit-scrollbar { display: none; }
    .prayer-card { transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s, background-color .35s; }
    .prayer-card:hover { transform: translateY(-8px) scale(1.04); box-shadow: 0 20px 40px -12px rgba(251,191,36,.35); }
    .prayer-card .p-icon { transition: transform .5s cubic-bezier(.22,1,.36,1); }
    .prayer-card:hover .p-icon { transform: rotate(-12deg) scale(1.25); }
    @@keyframes nextPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(251,191,36,.55); } 70% { box-shadow: 0 0 0 16px rgba(251,191,36,0); } }
    .prayer-next { animation: nextPulse 2.2s ease-out infinite; }

    /* ---------- Countdown digits ---------- */
    .cd-box { min-width: 3.1rem; }
    @@keyframes tick { 0% { transform: translateY(-35%); opacity: 0; } 100% { transform: none; opacity: 1; } }
    .cd-tick { animation: tick .35s cubic-bezier(.22,1,.36,1); }
    @@keyframes urgent { 0%,100% { color: #fbbf24; } 50% { color: #fb7185; } }
    .cd-urgent .cd-num { animation: urgent 1s ease-in-out infinite; }

    /* ---------- Cards: shine & lift ---------- */
    .card-lift { transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s; }
    .card-lift:hover { transform: translateY(-10px); box-shadow: 0 30px 50px -20px rgba(6,78,59,.35); }
    .shine { position: relative; overflow: hidden; }
    .shine::after {
        content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.35), transparent);
        transform: skewX(-20deg); transition: left .9s ease; pointer-events: none;
    }
    .card-lift:hover .shine::after, .shine:hover::after { left: 130%; }

    /* ---------- Quick action ---------- */
    .qa-item { transition: transform .35s cubic-bezier(.22,1,.36,1), background-color .3s; border-radius: 1.25rem; padding: .5rem; margin: -.5rem; }
    .qa-item:hover { transform: translateY(-4px); background-color: rgb(248 250 252); }
    .qa-icon { transition: transform .5s cubic-bezier(.34,1.56,.64,1), background-color .3s, color .3s; }
    .qa-item:hover .qa-icon { transform: rotate(-8deg) scale(1.12); }
    .arrow-slide i { transition: transform .3s ease; }
    .arrow-slide:hover i, .group:hover .arrow-slide i { transform: translateX(5px); }

    /* ---------- Buttons ---------- */
    .btn { position: relative; overflow: hidden; transition: transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s, background-color .25s, color .25s; }
    .btn:hover { transform: translateY(-3px); }
    .btn:active { transform: translateY(0) scale(.97); }
    .ripple-dot { position: absolute; border-radius: 9999px; background: rgba(255,255,255,.45); transform: scale(0); animation: ripple .65s ease-out forwards; pointer-events: none; }
    @@keyframes ripple { to { transform: scale(4); opacity: 0; } }

    /* ---------- Section badge dot ---------- */
    @@keyframes ping-soft { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(2.4); opacity: 0; } }
    .live-dot::after { content: ''; position: absolute; inset: 0; border-radius: 9999px; background: inherit; animation: ping-soft 1.8s ease-out infinite; }

    /* ---------- Toast & scroll UI ---------- */
    #toast { transform: translate(-50%, 30px); opacity: 0; transition: transform .45s cubic-bezier(.34,1.56,.64,1), opacity .3s; pointer-events: none; }
    #toast.show { transform: translate(-50%, 0); opacity: 1; }
    #scroll-progress { transform-origin: left; transform: scaleX(0); }
    #back-top { transform: translateY(20px) scale(.8); opacity: 0; pointer-events: none; transition: all .35s cubic-bezier(.34,1.56,.64,1); }
    #back-top.show { transform: none; opacity: 1; pointer-events: auto; }

    /* ---------- Respect reduced motion ---------- */
    @@media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; }
        .reveal-ready .reveal { opacity: 1; transform: none; }
        html { scroll-behavior: auto; }
    }
</style>
<script>document.documentElement.classList.add('reveal-ready');</script>

<!-- Scroll progress bar -->
<div class="fixed top-0 left-0 right-0 h-1 z-[60] pointer-events-none">
    <div id="scroll-progress" class="h-full bg-gradient-to-r from-amber-300 via-amber-400 to-emerald-400"></div>
</div>

<!-- Hero Section with Prayer Times Banner -->
<section class="relative pattern-bg text-white pt-12 pb-24 overflow-hidden border-b-4 border-amber-400">
    <!-- Decorative layers -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-emerald-950/40 to-emerald-950"></div>
    <div class="blob-a absolute -top-32 -right-32 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="blob-b absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <i class="fa-solid fa-star star text-xs top-16 left-[12%]" style="--d:0s"></i>
    <i class="fa-solid fa-star star text-[10px] top-32 right-[18%]" style="--d:1.2s"></i>
    <i class="fa-solid fa-star star text-sm top-48 left-[28%] hidden sm:block" style="--d:2.1s"></i>
    <i class="fa-solid fa-star star text-[10px] top-24 right-[40%] hidden sm:block" style="--d:.7s"></i>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <span class="hero-in inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-emerald-800/80 text-amber-300 border border-emerald-700/60 shadow-inner mb-4 hover:scale-105 transition-transform cursor-default" style="--d:50ms">
                <i class="fa-solid fa-moon text-amber-400"></i> {{ $prayerSchedule['date_hijri'] ?? 'Jumadil Awwal 1448 H' }}
            </span>
            <h1 class="hero-in text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-3" style="--d:170ms">
                Selamat Datang di <span class="text-shimmer">{{ $setting->name ?? 'Masjid Al-Kautsar' }}</span>
            </h1>
            <p class="hero-in text-base sm:text-xl text-emerald-100/90 font-medium leading-relaxed" style="--d:290ms">
                {{ $setting->tagline ?? 'Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor' }}
            </p>
        </div>

        <!-- Next Prayer Countdown Card -->
        <div class="hero-in max-w-xl mx-auto mb-8" style="--d:410ms">
            <div id="countdown-card" class="bg-gradient-to-r from-emerald-800/90 to-emerald-900/90 border border-emerald-600/60 rounded-3xl p-5 shadow-2xl backdrop-blur-md hover:border-amber-400/60 hover:shadow-amber-400/10 transition-all duration-500">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-400 text-emerald-950 flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                            <i class="fa-solid fa-hourglass-half animate-spin" style="animation-duration: 8s;"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-amber-300 uppercase tracking-wider font-bold">Waktu Sholat Berikutnya</p>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                                {{ $prayerSchedule['next_prayer']['name'] ?? 'Subuh' }} &bull; <span class="font-mono text-amber-300">{{ $prayerSchedule['next_prayer']['time'] ?? '04:10' }}</span> WIB
                            </h2>
                        </div>
                    </div>
                    <div class="bg-emerald-950/80 px-4 py-2.5 rounded-2xl border border-emerald-700/60">
                        <p class="text-[11px] text-emerald-300 font-semibold mb-1">Menuju Adzan</p>
                        <div id="prayer-countdown" class="flex items-center justify-center gap-1.5 font-mono font-black text-amber-400">
                            <div class="cd-box text-center"><span id="cd-h" class="cd-num block text-xl sm:text-2xl">--</span><span class="text-[9px] text-emerald-400 font-semibold uppercase">jam</span></div>
                            <span class="text-xl -mt-3 text-emerald-500">:</span>
                            <div class="cd-box text-center"><span id="cd-m" class="cd-num block text-xl sm:text-2xl">--</span><span class="text-[9px] text-emerald-400 font-semibold uppercase">menit</span></div>
                            <span class="text-xl -mt-3 text-emerald-500">:</span>
                            <div class="cd-box text-center"><span id="cd-s" class="cd-num block text-xl sm:text-2xl">--</span><span class="text-[9px] text-emerald-400 font-semibold uppercase">detik</span></div>
                        </div>
                        <p id="cd-done" class="hidden text-sm font-bold text-amber-300 mt-1">Waktunya Sholat 🕌</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Prayer Timetable: swipe on mobile, grid on laptop -->
        <div class="hero-in relative max-w-6xl mx-auto" style="--d:530ms">
            @php
                $timings = $prayerSchedule['timings'];
                $delays = $prayerSchedule['delays'];
                $iqamahTimes = $prayerSchedule['iqamah'];
                $nextName = $prayerSchedule['next_prayer']['name'] ?? '';
                $icons = [
                    'Imsak' => 'fa-utensils',
                    'Subuh' => 'fa-sun',
                    'Terbit' => 'fa-cloud-sun',
                    'Dhuha' => 'fa-sun',
                    'Dzuhur' => 'fa-sun',
                    'Ashar' => 'fa-cloud-sun',
                    'Maghrib' => 'fa-moon',
                    'Isya' => 'fa-star-and-crescent',
                ];
            @endphp

            <div id="prayer-scroll" class="prayer-scroll flex lg:grid lg:grid-cols-8 gap-3 overflow-x-auto lg:overflow-visible snap-x snap-mandatory pt-4 pb-5 px-1 -mx-1">
                @foreach ($timings as $pName => $pTime)
                    @php
                        $isNext = ($pName === $nextName);
                    @endphp
                    <div data-next="{{ $isNext ? '1' : '0' }}" class="prayer-card relative snap-center flex-shrink-0 w-[8.5rem] sm:w-36 lg:w-auto rounded-2xl p-3.5 text-center cursor-default {{ $isNext ? 'prayer-next bg-gradient-to-b from-amber-400 to-amber-500 text-emerald-950 shadow-xl shadow-amber-400/20 lg:scale-105 ring-4 ring-amber-300/40 z-10' : 'bg-emerald-900/70 hover:bg-emerald-800/90 text-white border border-emerald-700/60 shadow-md backdrop-blur-sm' }}">
                        @if ($isNext)
                            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-2 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-emerald-900 text-amber-300 shadow whitespace-nowrap">
                                Berikutnya
                            </span>
                        @endif
                        <div class="p-icon text-lg mb-1 {{ $isNext ? 'text-emerald-950' : 'text-amber-300' }}">
                            <i class="fa-solid {{ $icons[$pName] ?? 'fa-clock' }}"></i>
                        </div>
                        <h3 class="text-xs uppercase font-extrabold tracking-wider {{ $isNext ? 'text-emerald-950' : 'text-emerald-200' }}">
                            {{ $pName }}
                        </h3>
                        <p class="text-xl sm:text-2xl font-black font-mono my-0.5 tracking-tight {{ $isNext ? 'text-emerald-950' : 'text-white' }}">
                            {{ $pTime }}
                        </p>
                        @if (isset($iqamahTimes[$pName]))
                            <div class="mt-1 pt-1 border-t {{ $isNext ? 'border-emerald-950/20 text-emerald-950' : 'border-emerald-800 text-emerald-300' }} text-[11px] font-semibold">
                                Iqamah: {{ $iqamahTimes[$pName] }}
                            </div>
                        @else
                            <div class="mt-1 pt-1 border-t {{ $isNext ? 'border-emerald-950/20 text-emerald-950' : 'border-emerald-800 text-emerald-400/60' }} text-[11px]">
                                Bogor (WIB)
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <p class="lg:hidden text-center text-[11px] text-emerald-300/70 -mt-1"><i class="fa-solid fa-hand-pointer mr-1"></i> Geser untuk melihat semua waktu sholat</p>
        </div>

        <div class="hero-in text-center mt-5 text-xs text-emerald-200/80" style="--d:650ms">
            <span>* Jadwal dihitung otomatis via API Aladhan (Metode Kemenag RI, Koordinat Kota Bogor).</span>
            <a href="{{ route('prayer.schedule') }}" class="arrow-slide inline-flex items-center gap-1 text-amber-300 hover:text-amber-200 font-semibold ml-1 underline-offset-4 hover:underline">
                Lihat Kalender Sebulan <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</section>

<!-- Quick Action Highlights Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
    <div class="reveal bg-white rounded-3xl p-5 sm:p-7 shadow-xl border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <!-- Quick 1: Friday Schedule Spotlight -->
        <div class="qa-item flex items-start gap-4">
            <div class="qa-icon w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-100">
                <i class="fa-solid fa-kaaba"></i>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Jumat Terdekat
                </span>
                <h2 class="text-base font-extrabold text-slate-900 mt-1 line-clamp-1">
                    {{ $nextFriday->khatib ?? 'Prof. Dr. KH. Didin Hafidhuddin, M.Sc.' }}
                </h2>
                <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">
                    Tema: {{ $nextFriday->theme ?? 'Membangun Ketahanan Keluarga Berbasis Al-Qur\'an' }}
                </p>
                <a href="{{ route('friday.schedule') }}" class="arrow-slide inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-1.5">
                    Jadwal Lengkap Khutbah <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Quick 2: WhatsApp Layanan Jamaah -->
        <div class="qa-item flex items-start gap-4 md:border-l md:border-slate-100 md:pl-6">
            <div class="qa-icon w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-100">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Layanan Jamaah
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">Konsultasi & Informasi DKM</h3>
                <p class="text-xs text-slate-500 mt-0.5">Hubungi pengurus untuk zakat, infak, atau sewa aula.</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}?text={{ urlencode('Assalamu\'alaikum DKM Masjid Al-Kautsar, saya ingin bertanya:') }}" target="_blank" rel="noopener" class="arrow-slide inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-1.5">
                    Kirim Pesan WhatsApp <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Quick 3: Infaq Digital -->
        <div class="qa-item flex items-start gap-4 md:border-l md:border-slate-100 md:pl-6">
            <div class="qa-icon w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0 border border-amber-100">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                    Infaq Operasional
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">{{ $setting->bank_name ?? 'Bank Syariah Indonesia' }}</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                <a href="{{ route('donation') }}" class="arrow-slide inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-700 mt-1.5">
                    Salurkan Infaq & Sedekah <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Activities & Kajian Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div class="reveal reveal-left">
            <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-2">
                <span class="live-dot relative w-2 h-2 rounded-full bg-emerald-500"></span> Agenda Mendatang
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Kajian & Kegiatan Masjid
            </h2>
            <p class="text-base text-slate-500 mt-1">Terbuka untuk umum kaum muslimin dan muslimat.</p>
        </div>
        <a href="{{ route('activities.index') }}" class="btn reveal reveal-right self-start sm:self-auto inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-bold text-sm arrow-slide">
            Lihat Semua Jadwal <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @forelse ($upcomingActivities as $activity)
            <div class="reveal card-lift tilt bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md flex flex-col group" style="--d: {{ $loop->index * 120 }}ms">
                <div class="shine relative h-48 bg-emerald-950 overflow-hidden">
                    @if ($activity->image)
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 group-hover:rotate-1 transition duration-700 ease-out">
                    @else
                        <div class="w-full h-full pattern-bg flex items-center justify-center text-emerald-300">
                            <i class="fa-solid fa-book-quran text-6xl opacity-30 group-hover:opacity-60 group-hover:scale-110 transition duration-500"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-2xl shadow-md text-center group-hover:-translate-y-1 transition-transform duration-300">
                        <span class="block text-xs font-bold text-emerald-700 uppercase">{{ $activity->date->translatedFormat('M') }}</span>
                        <span class="block text-xl font-extrabold text-slate-900 font-mono leading-none">{{ $activity->date->format('d') }}</span>
                    </div>
                    <div class="absolute top-4 right-4 bg-emerald-900/90 text-amber-300 text-xs font-bold px-3 py-1 rounded-full shadow group-hover:scale-105 transition-transform duration-300">
                        {{ $activity->category }}
                    </div>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-2">
                            <a href="{{ route('activities.show', $activity->slug) }}">
                                {{ $activity->title }}
                            </a>
                        </h3>
                        @if ($activity->ustadz)
                            <p class="text-sm font-semibold text-emerald-800 flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-user-tie text-emerald-600"></i>
                                <span>{{ $activity->ustadz }}</span>
                            </p>
                        @endif
                        <div class="space-y-1 text-xs text-slate-500 mb-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-clock text-emerald-600 w-4"></i>
                                <span>Pukul {{ $activity->time_start }} - {{ $activity->time_end ?? 'Selesai' }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-emerald-600 w-4"></i>
                                <span class="line-clamp-1">{{ $activity->location }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('activities.show', $activity->slug) }}" class="btn mt-2 w-full text-center py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white font-bold text-xs">
                        Detail Kegiatan & Lokasi &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="reveal col-span-1 md:col-span-3 text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 mb-2"></i>
                <p>Belum ada agenda kajian mendatang yang dijadwalkan.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Latest News & Announcements Section -->
<section class="bg-emerald-50/60 py-16 border-y border-emerald-100/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div class="reveal reveal-left">
                <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-white px-3 py-1 rounded-full mb-2 border border-emerald-200">
                    <i class="fa-solid fa-bullhorn text-amber-500"></i> Warta Masjid
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Berita & Pengumuman Terbaru
                </h2>
                <p class="text-base text-slate-500 mt-1">Informasi terkini seputar kegiatan dan perkembangan DKM.</p>
            </div>
            <a href="{{ route('news.index') }}" class="btn reveal reveal-right self-start sm:self-auto inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-emerald-600 hover:text-white text-slate-700 font-bold text-sm shadow-sm arrow-slide">
                Semua Berita <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            @forelse ($latestNews as $news)
                <article class="reveal card-lift bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md flex flex-col group" style="--d: {{ $loop->index * 120 }}ms">
                    <div class="shine relative h-48 bg-slate-100 overflow-hidden">
                        @if ($news->image)
                            <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-emerald-950 flex items-center justify-center text-emerald-400">
                                <i class="fa-solid fa-newspaper text-5xl opacity-30 group-hover:opacity-60 group-hover:-rotate-6 group-hover:scale-110 transition duration-500"></i>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 text-xs font-bold px-3 py-1 rounded-full shadow group-hover:scale-105 transition-transform duration-300 {{ $news->category === 'Pengumuman' ? 'bg-amber-400 text-emerald-950' : 'bg-emerald-700 text-white' }}">
                            {{ $news->category }}
                        </span>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-400 mb-2">
                                <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> {{ $news->published_at ? $news->published_at->translatedFormat('d M Y') : $news->created_at->translatedFormat('d M Y') }}</span>
                                <span>&bull;</span>
                                <span><i class="fa-regular fa-eye mr-1"></i> {{ $news->views }} kali dibaca</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-2">
                                <a href="{{ route('news.show', $news->slug) }}">
                                    {{ $news->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                                {{ $news->summary ?? Str::limit(strip_tags($news->content), 120) }}
                            </p>
                        </div>

                        <a href="{{ route('news.show', $news->slug) }}" class="arrow-slide inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="reveal col-span-1 md:col-span-3 text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                    <p>Belum ada pengumuman terbaru.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- About Masjid Teaser & Google Maps Location -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="reveal bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        <div class="p-6 sm:p-12 flex flex-col justify-center">
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3 self-start">
                <i class="fa-solid fa-mosque"></i> Profil Singkat
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                {{ $setting->name ?? 'Masjid Al-Kautsar' }} Kota Bogor
            </h2>
            <p class="text-base text-slate-600 leading-relaxed mb-6">
                {{ Str::limit($setting->history, 320, '...') }}
            </p>
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="group bg-slate-50 hover:bg-emerald-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <p class="text-2xl font-black text-emerald-700 font-mono group-hover:scale-105 origin-left transition-transform"><span data-count="1500" data-suffix="+">1.500+</span></p>
                    <p class="text-xs text-slate-500 font-medium">Kapasitas Jamaah</p>
                </div>
                <div class="group bg-slate-50 hover:bg-emerald-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <p class="text-2xl font-black text-emerald-700 font-mono group-hover:scale-105 origin-left transition-transform"><span data-count="2500" data-suffix=" m²">2.500 m²</span></p>
                    <p class="text-xs text-slate-500 font-medium">Luas Lahan Wakaf</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('profile') }}" class="btn px-6 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-md hover:shadow-emerald-700/30 hover:shadow-xl">
                    Profil Lengkap & Struktur DKM
                </a>
                <a href="https://maps.google.com/?q={{ $setting->latitude }},{{ $setting->longitude }}" target="_blank" rel="noopener" class="btn px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-700"></i> Buka Petunjuk Arah
                </a>
            </div>
        </div>

        <div class="h-80 lg:h-auto min-h-[350px] relative bg-slate-100 overflow-hidden">
            @if ($setting->maps_embed_url)
                <iframe src="{{ $setting->maps_embed_url }}" class="w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Masjid Al-Kautsar Bogor"></iframe>
            @else
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15853.51888062955!2d106.806038!3d-6.597148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5e538ef3d39%3A0xb366b45a278940e7!2sBogor%20Timur%2C%20Bogor%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" class="w-full h-full border-0" allowfullscreen="" loading="lazy" title="Lokasi Masjid Al-Kautsar Bogor"></iframe>
            @endif
        </div>
    </div>
</section>

<!-- Donation CTA Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="reveal pattern-bg rounded-3xl p-6 sm:p-12 text-white relative overflow-hidden border-2 border-emerald-700 shadow-2xl">
        <div class="blob-a absolute -right-20 -top-20 w-72 h-72 bg-amber-400/15 rounded-full blur-3xl pointer-events-none"></div>
        <i class="fa-solid fa-hand-holding-heart absolute right-6 bottom-4 text-[9rem] text-amber-300/10 -rotate-12 pointer-events-none hidden md:block"></i>
        <div class="max-w-3xl relative z-10">
            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-300 bg-emerald-800/80 px-3 py-1 rounded-full mb-3">
                <i class="fa-solid fa-hand-holding-heart text-amber-400"></i> Berbagi Kebaikan
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight mb-3">
                Salurkan Infaq & Sedekah untuk Kemakmuran Masjid
            </h2>
            <p class="text-base text-emerald-100/90 leading-relaxed mb-6">
                Setiap rupiah yang Anda titipkan menjadi amal jariyah yang menopang kegiatan dakwah, santunan yatim-dhuafa, serta pemeliharaan sarana ibadah jamaah di Kota Bogor.
            </p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <div class="bg-emerald-950/90 border border-emerald-600/60 hover:border-amber-400/60 px-5 py-3 rounded-2xl flex items-center justify-between gap-4 transition-colors duration-300">
                    <div>
                        <p class="text-[11px] text-amber-300 font-bold uppercase tracking-wider">{{ $setting->bank_name ?? 'BSI' }}</p>
                        <p class="text-xl font-black font-mono text-white tracking-wider" id="bsi-acc-number">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                        <p class="text-[11px] text-emerald-300">a.n {{ $setting->bank_account_holder ?? 'DKM Masjid Al-Kautsar Bogor' }}</p>
                    </div>
                    <button type="button" onclick="copyRekening(this)" class="btn p-3 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-amber-300 shadow" title="Salin Nomor Rekening" aria-label="Salin Nomor Rekening">
                        <i class="fa-regular fa-copy text-lg"></i>
                    </button>
                </div>
                <a href="{{ route('donation') }}" class="btn px-6 py-4 rounded-2xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-extrabold text-sm text-center shadow-lg hover:shadow-amber-400/30 hover:shadow-2xl">
                    Lihat Program Infaq & QRIS &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 left-1/2 z-[70] bg-emerald-900 text-amber-300 text-sm font-semibold px-5 py-3 rounded-2xl shadow-2xl border border-emerald-600 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> <span id="toast-text">Nomor rekening berhasil disalin!</span>
</div>

<!-- Back to top -->
<button id="back-top" type="button" aria-label="Kembali ke atas" class="fixed bottom-6 right-5 z-50 w-12 h-12 rounded-full bg-emerald-700 hover:bg-emerald-600 text-amber-300 shadow-xl flex items-center justify-center hover:-translate-y-1">
    <i class="fa-solid fa-arrow-up"></i>
</button>
@endsection

@push('scripts')
<script>
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        const pad = (n) => String(n).padStart(2, '0');

        /* ---------- Live countdown (drift-free) ---------- */
        const hEl = document.getElementById('cd-h');
        const mEl = document.getElementById('cd-m');
        const sEl = document.getElementById('cd-s');
        const cdWrap = document.getElementById('prayer-countdown');
        const cdDone = document.getElementById('cd-done');
        const target = Date.now() + {{ (int) ($prayerSchedule['next_prayer']['seconds_remaining'] ?? 0) }} * 1000;
        const last = { h: '', m: '', s: '' };

        function setDigit(el, key, val) {
            if (last[key] === val) return;
            last[key] = val;
            el.textContent = val;
            if (!reduceMotion) {
                el.classList.remove('cd-tick');
                void el.offsetWidth;
                el.classList.add('cd-tick');
            }
        }

        function updateCountdown() {
            const sec = Math.max(0, Math.round((target - Date.now()) / 1000));
            if (sec <= 0) {
                cdWrap.classList.add('hidden');
                cdDone.classList.remove('hidden');
                return false;
            }
            setDigit(hEl, 'h', pad(Math.floor(sec / 3600)));
            setDigit(mEl, 'm', pad(Math.floor((sec % 3600) / 60)));
            setDigit(sEl, 's', pad(sec % 60));
            cdWrap.classList.toggle('cd-urgent', sec <= 600);
            return true;
        }

        if (updateCountdown()) {
            const timer = setInterval(() => { if (!updateCountdown()) clearInterval(timer); }, 1000);
        }

        /* ---------- Center the "next prayer" card on mobile ---------- */
        const scroller = document.getElementById('prayer-scroll');
        const nextCard = scroller && scroller.querySelector('[data-next="1"]');
        if (scroller && nextCard && window.innerWidth < 1024) {
            setTimeout(() => {
                scroller.scrollTo({
                    left: nextCard.offsetLeft - (scroller.clientWidth - nextCard.clientWidth) / 2,
                    behavior: reduceMotion ? 'auto' : 'smooth'
                });
            }, 900);
        }

        /* ---------- Scroll reveal ---------- */
        const revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-visible');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            revealEls.forEach((el) => io.observe(el));
        } else {
            revealEls.forEach((el) => el.classList.add('is-visible'));
        }

        /* ---------- Animated counters ---------- */
        const counters = document.querySelectorAll('[data-count]');
        function runCounter(el) {
            const end = parseInt(el.dataset.count, 10);
            const suffix = el.dataset.suffix || '';
            if (reduceMotion) { el.textContent = end.toLocaleString('id-ID') + suffix; return; }
            const dur = 1600, start = performance.now();
            (function step(now) {
                const p = Math.min((now - start) / dur, 1);
                const eased = 1 - Math.pow(1 - p, 4);
                el.textContent = Math.round(end * eased).toLocaleString('id-ID') + suffix;
                if (p < 1) requestAnimationFrame(step);
            })(start);
        }
        if ('IntersectionObserver' in window) {
            const co = new IntersectionObserver((entries) => {
                entries.forEach((e) => { if (e.isIntersecting) { runCounter(e.target); co.unobserve(e.target); } });
            }, { threshold: 0.6 });
            counters.forEach((el) => co.observe(el));
        }

        /* ---------- 3D tilt on cards (desktop only) ---------- */
        if (finePointer && !reduceMotion) {
            document.querySelectorAll('.tilt').forEach((card) => {
                card.addEventListener('pointermove', (e) => {
                    const r = card.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    card.style.transform = `translateY(-10px) perspective(900px) rotateX(${(-y * 5).toFixed(2)}deg) rotateY(${(x * 6).toFixed(2)}deg)`;
                });
                card.addEventListener('pointerleave', () => { card.style.transform = ''; });
            });
        }

        /* ---------- Ripple on buttons ---------- */
        document.querySelectorAll('.btn').forEach((btn) => {
            btn.addEventListener('pointerdown', (e) => {
                if (reduceMotion) return;
                const r = btn.getBoundingClientRect();
                const size = Math.max(r.width, r.height);
                const dot = document.createElement('span');
                dot.className = 'ripple-dot';
                dot.style.width = dot.style.height = size + 'px';
                dot.style.left = (e.clientX - r.left - size / 2) + 'px';
                dot.style.top = (e.clientY - r.top - size / 2) + 'px';
                btn.appendChild(dot);
                setTimeout(() => dot.remove(), 700);
            });
        });

        /* ---------- Scroll progress & back-to-top ---------- */
        const bar = document.getElementById('scroll-progress');
        const topBtn = document.getElementById('back-top');
        let ticking = false;
        function onScroll() {
            const doc = document.documentElement;
            const max = doc.scrollHeight - doc.clientHeight;
            const p = max > 0 ? doc.scrollTop / max : 0;
            bar.style.transform = `scaleX(${p})`;
            topBtn.classList.toggle('show', doc.scrollTop > 600);
            ticking = false;
        }
        window.addEventListener('scroll', () => {
            if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
        }, { passive: true });
        onScroll();
        topBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }));

        /* ---------- Toast ---------- */
        const toast = document.getElementById('toast');
        let toastTimer;
        window.showToast = function (msg) {
            document.getElementById('toast-text').textContent = msg;
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.remove('show'), 3000);
        };
    })();

    /* ---------- Copy account number ---------- */
    function copyRekening(btn) {
        const text = document.getElementById('bsi-acc-number').innerText.trim();
        const done = function () {
            window.showToast('Nomor rekening berhasil disalin!');
            const icon = btn.querySelector('i');
            icon.className = 'fa-solid fa-check text-lg';
            setTimeout(() => { icon.className = 'fa-regular fa-copy text-lg'; }, 2000);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(done);
        } else {
            const ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); done(); } catch (e) {}
            ta.remove();
        }
    }
</script>
@endpush