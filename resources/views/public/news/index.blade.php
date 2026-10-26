@extends('layouts.public')

@section('title', 'Berita & Pengumuman - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Informasi resmi, warta DKM, pengumuman ibadah, serta laporan kegiatan Masjid Al-Kautsar Bogor.')

@section('content')
<div class="pattern-bg text-white py-12 border-b-4 border-amber-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-800 text-amber-300 mb-3 border border-emerald-700">
            <i class="fa-solid fa-newspaper"></i> Warta & Informasi
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
            Berita & Pengumuman
        </h1>
        <p class="text-base sm:text-lg text-emerald-100/90 mt-2 max-w-2xl mx-auto">
            Ikuti kabar terkini seputar kegiatan ibadah, program sosial, dan pemberitahuan resmi DKM Masjid Al-Kautsar Bogor.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-md mb-10">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('news.index', ['q' => $search]) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition {{ !$category ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Semua
                </a>
                <a href="{{ route('news.index', ['category' => 'Berita', 'q' => $search]) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition {{ $category === 'Berita' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Berita Kegiatan
                </a>
                <a href="{{ route('news.index', ['category' => 'Pengumuman', 'q' => $search]) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition {{ $category === 'Pengumuman' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Pengumuman
                </a>
            </div>

            <!-- Search -->
            <form action="{{ route('news.index') }}" method="GET" class="w-full sm:w-72 relative">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari warta berita..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
            </form>
        </div>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($newsList as $news)
            <article class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="relative h-52 bg-slate-100 overflow-hidden">
                    @if ($news->image)
                        <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-emerald-950 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-newspaper text-6xl opacity-30"></i>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 text-xs font-bold px-3 py-1 rounded-full shadow {{ $news->category === 'Pengumuman' ? 'bg-amber-400 text-emerald-950 font-black' : 'bg-emerald-700 text-white' }}">
                        {{ $news->category }}
                    </span>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-xs text-slate-400 mb-2.5">
                            <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> {{ $news->published_at ? $news->published_at->translatedFormat('d M Y') : $news->created_at->translatedFormat('d M Y') }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-regular fa-eye mr-1"></i> {{ $news->views }} views</span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 mb-2.5">
                            <a href="{{ route('news.show', $news->slug) }}">
                                {{ $news->title }}
                            </a>
                        </h2>
                        <p class="text-sm text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                            {{ $news->summary ?? Str::limit(strip_tags($news->content), 120) }}
                        </p>
                    </div>

                    <a href="{{ route('news.show', $news->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 mt-2">
                        Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-dashed border-slate-200 text-slate-500">
                <i class="fa-solid fa-newspaper text-5xl text-slate-300 mb-3"></i>
                <p class="text-lg font-bold text-slate-700">Tidak ada berita yang ditemukan.</p>
                <p class="text-sm text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $newsList->links() }}
    </div>
</div>
@endsection
