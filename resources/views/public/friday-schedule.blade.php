@extends('layouts.public')

@section('title', 'Jadwal Khutbah Jumat - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Jadwal petugas sholat Jumat, khatib, imam, muadzin, serta tema khutbah di Masjid Al-Kautsar Bogor.')

@section('content')
<div class="pattern-bg text-white py-12 border-b-4 border-amber-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-800 text-amber-300 mb-3 border border-emerald-700">
            <i class="fa-solid fa-kaaba"></i> Sayyidul Ayyam
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
            Jadwal Khutbah & Petugas Sholat Jumat
        </h1>
        <p class="text-base sm:text-lg text-emerald-100/90 mt-2 max-w-2xl mx-auto">
            Daftar asatidz, khatib, imam, dan muadzin untuk sholat Jumat di Masjid Al-Kautsar Bogor.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Spotlight Card for Upcoming Friday -->
    @if ($nextFriday)
        <div class="bg-gradient-to-br from-emerald-900 to-emerald-950 text-white rounded-3xl p-6 sm:p-10 border border-emerald-700 shadow-2xl mb-12 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none text-emerald-400">
                <i class="fa-solid fa-mosque text-9xl"></i>
            </div>
            <div class="relative z-10 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-4 shadow">
                    <i class="fa-solid fa-star"></i> Khutbah Jumat Pekan Ini &bull; {{ $nextFriday->date->translatedFormat('d F Y') }}
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight text-white mb-2">
                    "{{ $nextFriday->theme }}"
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-emerald-800/80">
                    <div class="bg-emerald-800/60 p-4 rounded-2xl border border-emerald-700/60">
                        <p class="text-xs text-amber-300 uppercase font-bold tracking-wider">Khatib</p>
                        <p class="text-base font-extrabold text-white mt-1">{{ $nextFriday->khatib }}</p>
                    </div>
                    <div class="bg-emerald-800/60 p-4 rounded-2xl border border-emerald-700/60">
                        <p class="text-xs text-amber-300 uppercase font-bold tracking-wider">Imam</p>
                        <p class="text-base font-extrabold text-white mt-1">{{ $nextFriday->imam }}</p>
                    </div>
                    <div class="bg-emerald-800/60 p-4 rounded-2xl border border-emerald-700/60">
                        <p class="text-xs text-amber-300 uppercase font-bold tracking-wider">Muadzin / Bilal</p>
                        <p class="text-base font-extrabold text-white mt-1">{{ $nextFriday->muadzin ?? 'Petugas DKM' }}</p>
                    </div>
                </div>
                @if ($nextFriday->notes)
                    <p class="text-xs text-emerald-200 mt-4 italic">
                        <i class="fa-solid fa-info-circle mr-1"></i> {{ $nextFriday->notes }}
                    </p>
                @endif
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Upcoming Lineups List (2 Cols) -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                <i class="fa-regular fa-calendar-days text-emerald-700"></i>
                Daftar Jadwal Jumat Mendatang
            </h2>

            <div class="space-y-4">
                @forelse ($schedules as $sched)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-md hover:shadow-lg transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">
                                <i class="fa-regular fa-calendar"></i>
                                Jumat, {{ $sched->date->translatedFormat('d F Y') }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">
                                Masuk Waktu Dzuhur
                            </span>
                        </div>

                        <div class="my-4">
                            <h3 class="text-lg font-bold text-slate-900 leading-snug">
                                "{{ $sched->theme }}"
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div>
                                <span class="text-slate-400 block font-bold uppercase text-[10px]">Khatib</span>
                                <span class="font-extrabold text-slate-800 text-sm">{{ $sched->khatib }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-bold uppercase text-[10px]">Imam</span>
                                <span class="font-bold text-slate-800 text-sm">{{ $sched->imam }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-bold uppercase text-[10px]">Muadzin</span>
                                <span class="font-medium text-slate-700 text-sm">{{ $sched->muadzin ?? '-' }}</span>
                            </div>
                        </div>

                        @if ($sched->notes)
                            <p class="text-xs text-slate-500 mt-3 italic">
                                * {{ $sched->notes }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                        <p>Belum ada jadwal khutbah Jumat berikutnya.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Adab & Sunnah Hari Jumat (1 Col) -->
        <div>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xl sticky top-24">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Adab & Sunnah Hari Jumat</h3>
                        <p class="text-xs text-slate-400">Meraih pahala berlimpah di hari yang mulia</p>
                    </div>
                </div>

                <ul class="space-y-3.5 text-xs sm:text-sm text-slate-700">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span><strong>Mandi Jumat (Ghusl)</strong> dan bersiwak sebelum berangkat ke masjid.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span>Memotong kuku, merapikan kumis, dan memakai wewangian (bagi pria).</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span>Mengenakan pakaian terbaik (dianjurkan berwarna putih).</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span><strong>Datang lebih awal</strong> untuk mendapatkan keutamaan shaf pertama.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span>Sholat sunnah Tahiyyatul Masjid saat tiba di dalam masjid.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span>Membaca <strong>Surat Al-Kahfi</strong> dan memperbanyak shalawat atas Nabi ﷺ.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-1 flex-shrink-0"></i>
                        <span><strong>Diam dan seksama</strong> mendengarkan khutbah (tidak berbicara & menonaktifkan dering HP).</span>
                    </li>
                </ul>

                <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400 italic">
                        "Barangsiapa yang mandi pada hari Jumat... lalu mendengarkan khutbah dengan tenang, diampuni dosanya antara Jumat itu dan Jumat berikutnya." (HR. Muslim)
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
