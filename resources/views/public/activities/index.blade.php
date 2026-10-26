@extends('layouts.public')

@section('title', 'Jadwal Kegiatan & Kajian - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Daftar jadwal kajian rutin, tabligh akbar, kajian muslimah, dan kegiatan keagamaan di Masjid Al-Kautsar Bogor.')

@section('content')
<div class="pattern-bg text-white py-12 border-b-4 border-amber-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-800 text-amber-300 mb-3 border border-emerald-700">
            <i class="fa-solid fa-calendar-days"></i> Majelis Ilmu & Dakwah
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
            Kegiatan & Kajian Masjid
        </h1>
        <p class="text-base sm:text-lg text-emerald-100/90 mt-2 max-w-2xl mx-auto">
            Menuntut ilmu adalah kewajiban bagi setiap muslim. Mari makmurkan majelis ilmu bersama para asatidz Ahlus Sunnah.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-md mb-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            <!-- Category Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto w-full lg:w-auto pb-2 lg:pb-0 scrollbar-none">
                @foreach ($categories as $catKey => $catLabel)
                    @php
                        $isActive = ($category === $catKey) || (!$category && $catKey === 'semua');
                    @endphp
                    <a href="{{ route('activities.index', ['category' => $catKey, 'q' => $search]) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition {{ $isActive ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Search Input -->
            <form action="{{ route('activities.index') }}" method="GET" class="w-full lg:w-72 relative">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari ustadz / tema kajian..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
            </form>
        </div>
    </div>

    <!-- Activities Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($upcomingActivities as $activity)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="relative h-52 bg-emerald-950 overflow-hidden">
                    @if ($activity->image)
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full pattern-bg flex items-center justify-center text-emerald-300">
                            <i class="fa-solid fa-book-quran text-7xl opacity-30"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3.5 py-1.5 rounded-2xl shadow-md text-center">
                        <span class="block text-xs font-bold text-emerald-700 uppercase">{{ $activity->date->translatedFormat('M') }}</span>
                        <span class="block text-2xl font-extrabold text-slate-900 font-mono leading-none">{{ $activity->date->format('d') }}</span>
                    </div>
                    <span class="absolute top-4 right-4 bg-emerald-900/90 text-amber-300 text-xs font-bold px-3 py-1 rounded-full shadow">
                        {{ $activity->category }}
                    </span>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 mb-2">
                            <a href="{{ route('activities.show', $activity->slug) }}">
                                {{ $activity->title }}
                            </a>
                        </h2>
                        @if ($activity->ustadz)
                            <p class="text-sm font-bold text-emerald-800 flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-user-tie text-emerald-600"></i>
                                <span>{{ $activity->ustadz }}</span>
                            </p>
                        @endif
                        <div class="space-y-1.5 text-xs text-slate-500 mb-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-clock text-emerald-600 w-4"></i>
                                <span>{{ $activity->date->translatedFormat('l, d F Y') }} &bull; Pukul {{ $activity->time_start }} - {{ $activity->time_end ?? 'Selesai' }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-emerald-600 w-4"></i>
                                <span class="line-clamp-1">{{ $activity->location }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                            {{ $activity->description }}
                        </p>
                    </div>

                    <a href="{{ route('activities.show', $activity->slug) }}" class="mt-2 w-full text-center py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white font-bold text-xs transition duration-200">
                        Detail Informasi & Jadwal &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                <i class="fa-solid fa-calendar-xmark text-5xl text-slate-300 mb-3"></i>
                <p class="text-lg font-bold text-slate-700">Tidak ada kegiatan yang sesuai.</p>
                <p class="text-sm text-slate-400 mt-1">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                <a href="{{ route('activities.index') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-700 text-white rounded-xl text-xs font-bold">
                    Lihat Semua Kegiatan
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $upcomingActivities->links() }}
    </div>
</div>
@endsection
