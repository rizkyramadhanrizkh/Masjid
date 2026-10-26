@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Ringkasan & Aktivitas Panel DKM')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 sm:p-8 shadow-lg border border-emerald-700 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-700/80 text-amber-300 mb-2">
                <i class="fa-solid fa-mosque"></i> {{ $setting->name ?? 'Masjid Al-Kautsar' }} Bogor
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                Assalamu'alaikum, {{ Auth::user()->name }}!
            </h2>
            <p class="text-xs sm:text-sm text-emerald-200 mt-1">
                Kelola konten kegiatan, warta pengumuman, khutbah Jumat, dan pengaturan masjid dengan mudah tanpa perlu coding.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.news.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-bold text-xs shadow-md transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Tambah Berita
            </a>
            <a href="{{ route('admin.activities.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-plus"></i> Tambah Kegiatan
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1: News -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Berita</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $counts['news'] }}</p>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline mt-1 inline-block">
                    Kelola Berita &rarr;
                </a>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>

        <!-- Stat 2: Activities -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kegiatan Mendatang</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $counts['activities'] }}</p>
                <a href="{{ route('admin.activities.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline mt-1 inline-block">
                    Kelola Agenda &rarr;
                </a>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <!-- Stat 3: Friday Schedules -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal Khutbah</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $counts['friday_schedules'] }}</p>
                <a href="{{ route('admin.friday-schedules.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline mt-1 inline-block">
                    Kelola Khutbah &rarr;
                </a>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>

        <!-- Stat 4: DKM Members -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengurus DKM</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $counts['dkm_members'] }}</p>
                <a href="{{ route('admin.dkm-members.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline mt-1 inline-block">
                    Struktur DKM &rarr;
                </a>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Prayer Times Bar Widget for Today -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Jadwal Sholat Hari Ini (Bogor - Kemenag)</h3>
                <p class="text-xs text-slate-400">{{ $prayerSchedule['date'] }} &bull; {{ $prayerSchedule['date_hijri'] }}</p>
            </div>
            <a href="{{ route('admin.settings.edit') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-sliders"></i> Ubah Jeda Iqamah
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2 text-center text-xs">
            @foreach ($prayerSchedule['timings'] as $pName => $pTime)
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <p class="font-extrabold text-slate-400 uppercase text-[10px]">{{ $pName }}</p>
                    <p class="text-base font-black font-mono text-emerald-900 my-0.5">{{ $pTime }}</p>
                    @if (isset($prayerSchedule['iqamah'][$pName]))
                        <span class="text-[10px] text-emerald-700 font-semibold block">Iqamah {{ $prayerSchedule['iqamah'][$pName] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- 2 Column Section: Recent Activities & Recent News -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Activities List -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Agenda Kegiatan Terdekat</h3>
                <a href="{{ route('admin.activities.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                    Kelola Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($upcomingActivities as $act)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="truncate mr-3">
                            <span class="text-[10px] font-bold uppercase text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                {{ $act->category }}
                            </span>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-1">{{ $act->title }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $act->date->translatedFormat('d M Y') }} &bull; {{ $act->ustadz ?? 'Narasumber' }}</p>
                        </div>
                        <a href="{{ route('admin.activities.edit', $act) }}" class="p-2 rounded-xl text-slate-400 hover:text-emerald-700 hover:bg-white transition flex-shrink-0">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada agenda kegiatan mendatang.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent News List -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Berita & Pengumuman Baru</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                    Kelola Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($recentNews as $news)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="truncate mr-3">
                            <span class="text-[10px] font-bold uppercase {{ $news->category === 'Pengumuman' ? 'text-amber-800 bg-amber-100' : 'text-emerald-800 bg-emerald-100' }} px-2 py-0.5 rounded-full">
                                {{ $news->category }}
                            </span>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-1">{{ $news->title }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $news->published_at ? $news->published_at->translatedFormat('d M Y') : $news->created_at->translatedFormat('d M Y') }} &bull; {{ $news->views }} views</p>
                        </div>
                        <a href="{{ route('admin.news.edit', $news) }}" class="p-2 rounded-xl text-slate-400 hover:text-emerald-700 hover:bg-white transition flex-shrink-0">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada berita yang ditulis.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
