@extends('layouts.public')

@section('title', 'Beranda - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')

@section('content')
<!-- Hero Section with Prayer Times Banner -->
<section class="relative pattern-bg text-white pt-12 pb-24 overflow-hidden border-b-4 border-amber-400">
    <!-- Decorative Radial Glow -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-emerald-950/40 to-emerald-950"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-emerald-800/80 text-amber-300 border border-emerald-700/60 shadow-inner mb-4">
                <i class="fa-solid fa-moon text-amber-400"></i> {{ $prayerSchedule['date_hijri'] ?? 'Jumadil Awwal 1448 H' }}
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-3">
                Selamat Datang di <span class="text-amber-300">{{ $setting->name ?? 'Masjid Al-Kautsar' }}</span>
            </h1>
            <p class="text-base sm:text-xl text-emerald-100/90 font-medium leading-relaxed">
                {{ $setting->tagline ?? 'Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor' }}
            </p>
        </div>

        <!-- Next Prayer Countdown Card (Live Dynamic Badge) -->
        <div class="max-w-xl mx-auto mb-8 bg-gradient-to-r from-emerald-800/90 to-emerald-900/90 border border-emerald-600/60 rounded-3xl p-5 shadow-2xl backdrop-blur-md">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-400 text-emerald-950 flex items-center justify-center text-2xl shadow-md">
                        <i class="fa-solid fa-hourglass-half animate-spin" style="animation-duration: 8s;"></i>
                    </div>
                    <div>
                        <p class="text-xs text-amber-300 uppercase tracking-wider font-bold">Waktu Sholat Berikutnya</p>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                            {{ $prayerSchedule['next_prayer']['name'] ?? 'Subuh' }} &bull; <span class="font-mono text-amber-300">{{ $prayerSchedule['next_prayer']['time'] ?? '04:10' }}</span> WIB
                        </h2>
                    </div>
                </div>
                <div class="bg-emerald-950/80 px-4 py-2.5 rounded-2xl border border-emerald-700/60">
                    <p class="text-[11px] text-emerald-300 font-semibold mb-0.5">Menuju Adzan</p>
                    <div id="prayer-countdown" class="font-mono text-xl sm:text-2xl font-black text-amber-400 tracking-wider">
                        --:--:--
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Prayer Timetable Cards (Mobile Scrollable / Grid) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 max-w-6xl mx-auto">
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

            @foreach ($timings as $pName => $pTime)
                @php
                    $isNext = ($pName === $nextName);
                @endphp
                <div class="relative rounded-2xl p-3.5 text-center transition-all duration-300 {{ $isNext ? 'bg-gradient-to-b from-amber-400 to-amber-500 text-emerald-950 shadow-xl shadow-amber-400/20 scale-105 ring-4 ring-amber-300/40 z-10' : 'bg-emerald-900/70 hover:bg-emerald-800/80 text-white border border-emerald-700/60 shadow-md backdrop-blur-sm' }}">
                    @if ($isNext)
                        <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-2 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-emerald-900 text-amber-300 shadow">
                            Berikutnya
                        </span>
                    @endif
                    <div class="text-lg mb-1 {{ $isNext ? 'text-emerald-950' : 'text-amber-300' }}">
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

        <div class="text-center mt-6 text-xs text-emerald-200/80">
            <span>* Jadwal dihitung otomatis via API Aladhan (Metode Kemenag RI, Koordinat Kota Bogor).</span>
            <a href="{{ route('prayer.schedule') }}" class="text-amber-300 hover:underline font-semibold ml-1">
                Lihat Kalender Sebulan &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Quick Action Highlights Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
    <div class="bg-white rounded-3xl p-5 sm:p-7 shadow-xl border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <!-- Quick 1: Friday Schedule Spotlight -->
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-100">
                <i class="fa-solid fa-kaaba"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Jumat Terdekat
                </span>
                <h2 class="text-base font-extrabold text-slate-900 mt-1 line-clamp-1">
                    {{ $nextFriday->khatib ?? 'Prof. Dr. KH. Didin Hafidhuddin, M.Sc.' }}
                </h2>
                <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">
                    Tema: {{ $nextFriday->theme ?? 'Membangun Ketahanan Keluarga Berbasis Al-Qur\'an' }}
                </p>
                <a href="{{ route('friday.schedule') }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-1.5">
                    Jadwal Lengkap Khutbah <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Quick 2: WhatsApp Layanan Jamaah -->
        <div class="flex items-start gap-4 md:border-l md:border-slate-100 md:pl-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-100">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Layanan Jamaah
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">Konsultasi & Informasi DKM</h3>
                <p class="text-xs text-slate-500 mt-0.5">Hubungi pengurus untuk zakat, infak, atau sewa aula.</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}?text={{ urlencode('Assalamu\'alaikum DKM Masjid Al-Kautsar, saya ingin bertanya:') }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-1.5">
                    Kirim Pesan WhatsApp <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Quick 3: Infaq Digital -->
        <div class="flex items-start gap-4 md:border-l md:border-slate-100 md:pl-6">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0 border border-amber-100">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                    Infaq Operasional
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">{{ $setting->bank_name ?? 'Bank Syariah Indonesia' }}</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                <a href="{{ route('donation') }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-700 mt-1.5">
                    Salurkan Infaq & Sedekah <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Activities & Kajian Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-2">
                <i class="fa-solid fa-calendar-check"></i> Agenda Mendatang
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Kajian & Kegiatan Masjid
            </h2>
            <p class="text-base text-slate-500 mt-1">Terbuka untuk umum kaum muslimin dan muslimat.</p>
        </div>
        <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-bold text-sm transition">
            Lihat Semua Jadwal <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse ($upcomingActivities as $activity)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="relative h-48 bg-emerald-950 overflow-hidden">
                    @if ($activity->image)
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full pattern-bg flex items-center justify-center text-emerald-300">
                            <i class="fa-solid fa-book-quran text-6xl opacity-30"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-2xl shadow-md text-center">
                        <span class="block text-xs font-bold text-emerald-700 uppercase">{{ $activity->date->translatedFormat('M') }}</span>
                        <span class="block text-xl font-extrabold text-slate-900 font-mono leading-none">{{ $activity->date->format('d') }}</span>
                    </div>
                    <div class="absolute top-4 right-4 bg-emerald-900/90 text-amber-300 text-xs font-bold px-3 py-1 rounded-full shadow">
                        {{ $activity->category }}
                    </div>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 mb-2">
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

                    <a href="{{ route('activities.show', $activity->slug) }}" class="mt-2 w-full text-center py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white font-bold text-xs transition duration-200">
                        Detail Kegiatan & Lokasi &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
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
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700 bg-white px-3 py-1 rounded-full mb-2 border border-emerald-200">
                    <i class="fa-solid fa-bullhorn text-amber-500"></i> Warta Masjid
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Berita & Pengumuman Terbaru
                </h2>
                <p class="text-base text-slate-500 mt-1">Informasi terkini seputar kegiatan dan perkembangan DKM.</p>
            </div>
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-emerald-600 hover:text-white text-slate-700 font-bold text-sm transition shadow-sm">
                Semua Berita <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($latestNews as $news)
                <article class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        @if ($news->image)
                            <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-emerald-950 flex items-center justify-center text-emerald-400">
                                <i class="fa-solid fa-newspaper text-5xl opacity-30"></i>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 text-xs font-bold px-3 py-1 rounded-full shadow {{ $news->category === 'Pengumuman' ? 'bg-amber-400 text-emerald-950' : 'bg-emerald-700 text-white' }}">
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
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 mb-2">
                                <a href="{{ route('news.show', $news->slug) }}">
                                    {{ $news->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                                {{ $news->summary ?? Str::limit(strip_tags($news->content), 120) }}
                            </p>
                        </div>

                        <a href="{{ route('news.show', $news->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                    <p>Belum ada pengumuman terbaru.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- About Masjid Teaser & Google Maps Location -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        <div class="p-8 sm:p-12 flex flex-col justify-center">
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
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-2xl font-black text-emerald-700 font-mono">1.500+</p>
                    <p class="text-xs text-slate-500 font-medium">Kapasitas Jamaah</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-2xl font-black text-emerald-700 font-mono">2.500 m²</p>
                    <p class="text-xs text-slate-500 font-medium">Luas Lahan Wakaf</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('profile') }}" class="px-6 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-md transition">
                    Profil Lengkap & Struktur DKM
                </a>
                <a href="https://maps.google.com/?q={{ $setting->latitude }},{{ $setting->longitude }}" target="_blank" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-700"></i> Buka Petunjuk Arah
                </a>
            </div>
        </div>

        <div class="h-80 lg:h-auto min-h-[350px] relative bg-slate-100">
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
    <div class="pattern-bg rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden border-2 border-emerald-700 shadow-2xl">
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
                <div class="bg-emerald-950/90 border border-emerald-600/60 px-5 py-3 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] text-amber-300 font-bold uppercase tracking-wider">{{ $setting->bank_name ?? 'BSI' }}</p>
                        <p class="text-xl font-black font-mono text-white tracking-wider" id="bsi-acc-number">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                        <p class="text-[11px] text-emerald-300">a.n {{ $setting->bank_account_holder ?? 'DKM Masjid Al-Kautsar Bogor' }}</p>
                    </div>
                    <button type="button" onclick="copyRekening()" class="p-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-amber-300 transition shadow" title="Salin Nomor Rekening">
                        <i class="fa-regular fa-copy text-lg"></i>
                    </button>
                </div>
                <a href="{{ route('donation') }}" class="px-6 py-4 rounded-2xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-extrabold text-sm text-center shadow-lg transition">
                    Lihat Program Infaq & QRIS &rarr;
                </a>
            </div>
            <p id="copy-feedback" class="text-xs text-amber-300 font-semibold mt-2 hidden">
                <i class="fa-solid fa-check"></i> Nomor rekening berhasil disalin ke clipboard!
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Live Countdown to Next Prayer
    (function() {
        let remainingSeconds = {{ (int) ($prayerSchedule['next_prayer']['seconds_remaining'] ?? 0) }};
        const countdownEl = document.getElementById('prayer-countdown');

        function formatCountdown(sec) {
            if (sec <= 0) {
                return '00:00:00 (Waktunya Sholat)';
            }
            const hours = Math.floor(sec / 3600);
            const minutes = Math.floor((sec % 3600) / 60);
            const seconds = Math.floor(sec % 60);

            const pad = (n) => String(n).padStart(2, '0');
            return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
        }

        function updateCountdown() {
            if (countdownEl) {
                countdownEl.textContent = formatCountdown(remainingSeconds);
            }
            if (remainingSeconds > 0) {
                remainingSeconds--;
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    })();

    // Copy Account Number
    function copyRekening() {
        const text = document.getElementById('bsi-acc-number').innerText.trim();
        navigator.clipboard.writeText(text).then(function() {
            const feedback = document.getElementById('copy-feedback');
            if (feedback) {
                feedback.classList.remove('hidden');
                setTimeout(() => feedback.classList.add('hidden'), 3500);
            }
        });
    }
</script>
@endpush
