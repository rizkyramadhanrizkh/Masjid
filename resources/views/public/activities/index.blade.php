@extends('layouts.public')

@section('title', 'Jadwal Kegiatan & Kajian - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Daftar jadwal kajian rutin, tabligh akbar, kajian muslimah, dan kegiatan keagamaan di Masjid Al-Kautsar Bogor.')

@section('content')
<style>
    html { scroll-behavior: smooth; }

    /* Reveal on scroll */
    .reveal-ready .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .6s cubic-bezier(.2,.7,.2,1), transform .6s cubic-bezier(.2,.7,.2,1);
        transition-delay: var(--d, 0ms);
    }
    .reveal-ready .reveal.is-visible { opacity: 1; transform: none; }

    /* Header entrance */
    @@keyframes riseIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .rise { opacity: 0; animation: riseIn .7s cubic-bezier(.2,.7,.2,1) forwards; animation-delay: var(--d, 0ms); }

    /* Underline that grows from the left */
    .u-link { background-image: linear-gradient(currentColor, currentColor); background-size: 0 1.5px; background-repeat: no-repeat; background-position: 0 100%; transition: background-size .35s ease; }
    .group:hover .u-link, .u-link:hover { background-size: 100% 1.5px; }

    /* Filter tabs */
    .tab { position: relative; transition: background-color .25s, color .25s, transform .2s, box-shadow .25s; }
    .tab:hover { transform: translateY(-2px); }
    .tab:active { transform: scale(.96); }

    /* Activity card */
    .act-card { transition: transform .4s cubic-bezier(.2,.7,.2,1), box-shadow .4s, border-color .3s; }
    .act-card:hover { transform: translateY(-6px); box-shadow: 0 24px 40px -22px rgba(6,78,59,.45); border-color: rgb(167 243 208); }
    .act-card:active { transform: translateY(-2px) scale(.995); }
    .act-img { transition: transform .8s cubic-bezier(.2,.7,.2,1); }
    .act-card:hover .act-img { transform: scale(1.07); }
    .act-card .arrow-dot { transition: transform .35s cubic-bezier(.2,.7,.2,1), background-color .3s, color .3s; }
    .act-card:hover .arrow-dot { transform: translateX(4px) rotate(-45deg); background-color: rgb(4 120 87); color: #fff; }
    .act-card .date-tag { transition: transform .35s cubic-bezier(.2,.7,.2,1); transform-origin: top left; }
    .act-card:hover .date-tag { transform: rotate(-3deg) translateY(-2px); }
    .act-card .meta-row i { transition: transform .3s; }
    .act-card:hover .meta-row i { transform: scale(1.2); }

    /* Search */
    .search-wrap { transition: width .35s cubic-bezier(.2,.7,.2,1); }
    @@media (min-width: 1024px) { .search-wrap:focus-within { width: 22rem; } }
    .search-wrap:focus-within .s-icon { color: rgb(4 120 87); transform: scale(1.1); }
    .s-icon { transition: color .25s, transform .25s; }

    /* Badge pulse for "Hari ini" */
    @@keyframes beat { 0%,100% { box-shadow: 0 0 0 0 rgba(245,158,11,.5); } 60% { box-shadow: 0 0 0 7px rgba(245,158,11,0); } }
    .badge-today { animation: beat 2s infinite; }

    /* Pagination polish */
    .pager nav a, .pager nav span[aria-current] > span, .pager nav button { transition: transform .2s, background-color .2s, color .2s; }
    .pager nav a:hover { transform: translateY(-2px); }

    /* Toast-free ripple for buttons */
    .btn-press { transition: transform .2s, box-shadow .25s, background-color .25s; }
    .btn-press:hover { transform: translateY(-2px); }
    .btn-press:active { transform: scale(.96); }

    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }

    @@media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .001ms !important; transition-duration: .001ms !important; }
        .reveal-ready .reveal { opacity: 1; transform: none; }
        html { scroll-behavior: auto; }
    }
</style>
<script>document.documentElement.classList.add('reveal-ready');</script>

@php
    $totalActivities = method_exists($upcomingActivities, 'total') ? $upcomingActivities->total() : $upcomingActivities->count();
@endphp

<!-- Page header -->
<div class="pattern-bg text-white border-b-4 border-amber-400 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-emerald-950/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-16">
        <div class="max-w-3xl">
            <div class="rise flex items-center gap-3 text-xs font-bold uppercase tracking-[.2em] text-amber-300 mb-4" style="--d:40ms">
                <span class="h-px w-8 bg-amber-300/70"></span>
                Majelis Ilmu &amp; Dakwah
            </div>
            <h1 class="rise text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight" style="--d:140ms">
                Kegiatan &amp; Kajian Masjid
            </h1>
            <p class="rise text-base sm:text-lg text-emerald-100/85 mt-4 leading-relaxed" style="--d:240ms">
                Menuntut ilmu adalah kewajiban bagi setiap muslim. Mari makmurkan majelis ilmu bersama para asatidz Ahlus Sunnah.
            </p>
            <div class="rise mt-6 inline-flex items-center gap-2 text-sm text-emerald-100/80" style="--d:340ms">
                <i class="fa-solid fa-calendar-days text-amber-300"></i>
                <span><strong class="text-white font-bold">{{ $totalActivities }}</strong> kegiatan ditemukan</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <!-- Filter Tabs & Search Bar -->
    <div class="reveal bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Category Tabs -->
            <div id="cat-tabs" class="no-scrollbar flex items-center gap-2 overflow-x-auto -mx-1 px-1 pb-1 lg:pb-0 snap-x">
                @foreach ($categories as $catKey => $catLabel)
                    @php
                        $isActive = ($category === $catKey) || (!$category && $catKey === 'semua');
                    @endphp
                    <a href="{{ route('activities.index', ['category' => $catKey, 'q' => $search]) }}"
                       @if ($isActive) data-active="1" aria-current="page" @endif
                       class="tab snap-start px-4 py-2 rounded-full text-xs sm:text-sm font-bold whitespace-nowrap {{ $isActive ? 'bg-emerald-700 text-white shadow-md shadow-emerald-700/25' : 'bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-600' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Search Input -->
            <form action="{{ route('activities.index') }}" method="GET" id="search-form" class="search-wrap w-full lg:w-72 relative">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input id="search-input" type="text" name="q" value="{{ $search }}" placeholder="Cari ustadz / tema kajian..." autocomplete="off"
                       class="w-full pl-10 pr-16 py-2.5 rounded-full border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent bg-slate-50 focus:bg-white transition">
                <i class="s-icon fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <button type="button" id="search-clear" aria-label="Hapus pencarian"
                        class="{{ $search ? '' : 'hidden' }} absolute right-9 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-500 text-[10px] flex items-center justify-center transition hover:rotate-90">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <kbd class="hidden lg:flex absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 items-center justify-center rounded bg-white border border-slate-200 text-[10px] font-mono text-slate-400">/</kbd>
            </form>
        </div>
    </div>

    <!-- Activities Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        @forelse ($upcomingActivities as $activity)
            @php
                $daysLeft = (int) round(now()->startOfDay()->diffInDays($activity->date->copy()->startOfDay(), false));
            @endphp
            <article class="reveal act-card relative bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm flex flex-col group" style="--d: {{ ($loop->index % 3) * 100 }}ms">
                <div class="relative h-52 bg-emerald-950 overflow-hidden">
                    @if ($activity->image)
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" loading="lazy" class="act-img w-full h-full object-cover">
                    @else
                        <div class="act-img w-full h-full pattern-bg flex items-center justify-center text-emerald-300">
                            <i class="fa-solid fa-book-quran text-7xl opacity-30"></i>
                        </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/50 to-transparent"></div>

                    <div class="date-tag absolute top-4 left-4 bg-white px-3.5 py-1.5 rounded-xl shadow-lg text-center border-b-4 border-amber-400">
                        <span class="block text-[11px] font-bold text-emerald-700 uppercase tracking-wide">{{ $activity->date->translatedFormat('M') }}</span>
                        <span class="block text-2xl font-extrabold text-slate-900 font-mono leading-none">{{ $activity->date->format('d') }}</span>
                    </div>
                    <span class="absolute top-4 right-4 bg-emerald-900/90 backdrop-blur-sm text-amber-300 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $activity->category }}
                    </span>

                    @if ($daysLeft === 0)
                        <span class="badge-today absolute bottom-3 left-4 bg-amber-400 text-emerald-950 text-[11px] font-extrabold px-2.5 py-1 rounded-full">Hari ini</span>
                    @elseif ($daysLeft === 1)
                        <span class="absolute bottom-3 left-4 bg-white/95 text-emerald-800 text-[11px] font-extrabold px-2.5 py-1 rounded-full">Besok</span>
                    @elseif ($daysLeft > 1 && $daysLeft <= 7)
                        <span class="absolute bottom-3 left-4 bg-white/95 text-emerald-800 text-[11px] font-bold px-2.5 py-1 rounded-full">{{ $daysLeft }} hari lagi</span>
                    @endif
                </div>

                <div class="p-5 sm:p-6 flex-grow flex flex-col">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-2">
                        <a href="{{ route('activities.show', $activity->slug) }}" class="after:absolute after:inset-0 after:z-10">
                            {{ $activity->title }}
                        </a>
                    </h2>
                    @if ($activity->ustadz)
                        <p class="text-sm font-semibold text-emerald-800 flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-user-tie text-emerald-600"></i>
                            <span class="u-link">{{ $activity->ustadz }}</span>
                        </p>
                    @endif
                    <div class="space-y-1.5 text-xs text-slate-500 mb-4">
                        <div class="meta-row flex items-start gap-2">
                            <i class="fa-regular fa-clock text-emerald-600 w-4 mt-0.5"></i>
                            <span>{{ $activity->date->translatedFormat('l, d F Y') }} &bull; {{ $activity->time_start }} - {{ $activity->time_end ?? 'Selesai' }} WIB</span>
                        </div>
                        <div class="meta-row flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-emerald-600 w-4"></i>
                            <span class="line-clamp-1">{{ $activity->location }}</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-[13px] text-slate-600 line-clamp-2 leading-relaxed mb-5">
                        {{ $activity->description }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-dashed border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-700">Detail informasi &amp; jadwal</span>
                        <span class="arrow-dot w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </article>
        @empty
            <div class="reveal col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 text-slate-500">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300"></i>
                </div>
                <p class="text-lg font-bold text-slate-700">Tidak ada kegiatan yang sesuai.</p>
                <p class="text-sm text-slate-400 mt-1">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                <a href="{{ route('activities.index') }}" class="btn-press mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full text-xs font-bold shadow-md">
                    <i class="fa-solid fa-rotate-left"></i> Lihat Semua Kegiatan
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pager mt-12 reveal">
        {{ $upcomingActivities->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Reveal on scroll
        const els = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
            els.forEach((el) => io.observe(el));
        } else {
            els.forEach((el) => el.classList.add('is-visible'));
        }

        // Keep the active category tab in view on small screens
        const active = document.querySelector('#cat-tabs [data-active="1"]');
        if (active && window.innerWidth < 1024) {
            active.scrollIntoView({ inline: 'center', block: 'nearest', behavior: reduce ? 'auto' : 'smooth' });
        }

        // Search: clear button, "/" shortcut, loading icon on submit
        const input = document.getElementById('search-input');
        const clear = document.getElementById('search-clear');
        const form = document.getElementById('search-form');
        input.addEventListener('input', () => clear.classList.toggle('hidden', input.value === ''));
        clear.addEventListener('click', () => { input.value = ''; clear.classList.add('hidden'); input.focus(); });
        document.addEventListener('keydown', (e) => {
            const tag = (document.activeElement && document.activeElement.tagName) || '';
            if (e.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                e.preventDefault();
                input.focus();
            }
        });
        form.addEventListener('submit', () => {
            const icon = form.querySelector('.s-icon');
            icon.className = 's-icon fa-solid fa-circle-notch fa-spin absolute left-4 top-1/2 -translate-y-1/2 text-emerald-600 text-xs';
        });
    })();
</script>
@endpush