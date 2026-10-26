@extends('layouts.public')

@section('title', $news->title . ' - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', Str::limit(strip_tags($news->summary ?? $news->content), 150))

@section('content')
<!-- Breadcrumbs -->
<div class="bg-slate-100/80 border-b border-slate-200/60 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('news.index') }}" class="hover:text-emerald-700">Berita</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">{{ $news->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Article (2 Cols) -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-xl overflow-hidden">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $news->category === 'Pengumuman' ? 'bg-amber-100 text-amber-900 font-black' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $news->category }}
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs text-slate-500 font-medium">
                        <i class="fa-regular fa-calendar-check mr-1 text-emerald-600"></i>
                        {{ $news->published_at ? $news->published_at->translatedFormat('l, d F Y - H:i') : $news->created_at->translatedFormat('l, d F Y') }} WIB
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs text-slate-500">
                        <i class="fa-regular fa-eye mr-1 text-slate-400"></i> {{ $news->views }} kali dibaca
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
                    {{ $news->title }}
                </h1>

                <!-- Author info -->
                @if ($news->author)
                    <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Ditulis oleh</p>
                            <p class="text-sm font-bold text-slate-800">{{ $news->author->name }}</p>
                        </div>
                    </div>
                @endif

                @if ($news->image)
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-md">
                        <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-full h-auto object-cover max-h-[500px]">
                    </div>
                @endif

                @if ($news->summary)
                    <div class="bg-emerald-50/70 border-l-4 border-emerald-600 p-4 rounded-r-2xl mb-8 text-emerald-950 font-medium text-base italic leading-relaxed">
                        {{ $news->summary }}
                    </div>
                @endif

                <!-- Article Content with Elderly-friendly comfortable typography -->
                <div class="article-body prose prose-slate max-w-none text-slate-800 leading-relaxed text-base sm:text-lg mb-10 space-y-4">
                    {!! $news->content !!}
                </div>

                <!-- Share Box -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan Berita Ini:</p>
                    <div class="flex items-center gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm transition">
                            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin!')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-2 transition">
                            <i class="fa-regular fa-copy text-sm"></i> Salin Tautan
                        </button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar (1 Col) -->
        <div class="space-y-8">
            <!-- Sidebar: Recent News -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-md">
                <h3 class="text-lg font-extrabold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Warta Terbaru Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse ($recentNews as $rn)
                        <a href="{{ route('news.show', $rn->slug) }}" class="flex items-start gap-3.5 group p-2 rounded-2xl hover:bg-slate-50 transition">
                            @if ($rn->image)
                                <img src="{{ asset($rn->image) }}" alt="{{ $rn->title }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 border border-emerald-100">
                                    <i class="fa-solid fa-newspaper text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                    {{ $rn->category }}
                                </span>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-emerald-700 line-clamp-2 mt-0.5">
                                    {{ $rn->title }}
                                </h4>
                                <span class="text-[11px] text-slate-400 mt-1 block">
                                    {{ $rn->published_at ? $rn->published_at->translatedFormat('d M Y') : $rn->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada berita lainnya.</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('news.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900">
                        Lihat Semua Berita &rarr;
                    </a>
                </div>
            </div>

            <!-- Infaq Callout Box -->
            <div class="pattern-bg rounded-3xl p-6 text-white border border-emerald-700 shadow-md">
                <h3 class="text-lg font-extrabold mb-2 text-white">Infaq & Donasi DKM</h3>
                <p class="text-xs text-emerald-100/90 leading-relaxed mb-4">
                    Dukung kegiatan dakwah dan santunan sosial Masjid Al-Kautsar Bogor melalui rekening resmi BSI.
                </p>
                <div class="bg-emerald-950/80 p-3.5 rounded-xl border border-emerald-700/60 mb-4 font-mono text-center">
                    <p class="text-[11px] text-amber-300">{{ $setting->bank_name ?? 'BSI' }}</p>
                    <p class="text-lg font-bold text-white">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                </div>
                <a href="{{ route('donation') }}" class="w-full py-2.5 px-4 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-bold text-xs flex items-center justify-center gap-2 shadow transition">
                    Informasi Program Donasi &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
