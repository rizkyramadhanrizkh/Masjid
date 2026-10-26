@extends('layouts.public')

@section('title', $activity->title . ' - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', Str::limit(strip_tags($activity->description), 150))

@section('content')
<style>
    html { scroll-behavior: smooth; }

    .reveal-ready .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .6s cubic-bezier(.2,.7,.2,1), transform .6s cubic-bezier(.2,.7,.2,1);
        transition-delay: var(--d, 0ms);
    }
    .reveal-ready .reveal.is-visible { opacity: 1; transform: none; }

    @@keyframes riseIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .rise { opacity: 0; animation: riseIn .7s cubic-bezier(.2,.7,.2,1) forwards; animation-delay: var(--d, 0ms); }

    @@keyframes imgIn { from { opacity: 0; transform: scale(1.06); } to { opacity: 1; transform: none; } }
    .img-in { animation: imgIn 1.1s cubic-bezier(.2,.7,.2,1) both; }

    .u-link { background-image: linear-gradient(currentColor, currentColor); background-size: 0 1.5px; background-repeat: no-repeat; background-position: 0 100%; transition: background-size .35s ease; }
    .u-link:hover, .group:hover .u-link { background-size: 100% 1.5px; }

    /* Breadcrumb */
    .crumb { transition: color .2s; }
    .crumb:hover { color: rgb(4 120 87); }

    /* Presenter card */
    .speaker { transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .3s; }
    .speaker:hover { transform: translateY(-3px); box-shadow: 0 14px 28px -18px rgba(6,78,59,.5); border-color: rgb(110 231 183); }
    .speaker .sp-icon { transition: transform .45s cubic-bezier(.34,1.56,.64,1); }
    .speaker:hover .sp-icon { transform: rotate(-8deg) scale(1.1); }

    /* Detail tiles */
    .tile { transition: transform .35s cubic-bezier(.2,.7,.2,1), background-color .3s, border-color .3s, box-shadow .35s; }
    .tile:hover { transform: translateY(-4px); background-color: #fff; border-color: rgb(167 243 208); box-shadow: 0 16px 30px -20px rgba(6,78,59,.5); }
    .tile .t-icon { transition: transform .4s cubic-bezier(.34,1.56,.64,1), background-color .3s, color .3s; }
    .tile:hover .t-icon { transform: scale(1.12) rotate(-6deg); background-color: rgb(4 120 87); color: #fff; }

    /* Buttons */
    .btn { position: relative; overflow: hidden; transition: transform .25s cubic-bezier(.2,.7,.2,1), box-shadow .25s, background-color .25s, color .25s; }
    .btn:hover { transform: translateY(-3px); }
    .btn:active { transform: scale(.96); }
    .btn i { transition: transform .3s cubic-bezier(.34,1.56,.64,1); }
    .btn:hover i { transform: scale(1.2) rotate(-6deg); }
    .ripple-dot { position: absolute; border-radius: 9999px; background: rgba(255,255,255,.4); transform: scale(0); animation: ripple .6s ease-out forwards; pointer-events: none; }
    @@keyframes ripple { to { transform: scale(4); opacity: 0; } }

    /* Sidebar related item */
    .rel-item { transition: transform .3s cubic-bezier(.2,.7,.2,1), background-color .25s; }
    .rel-item:hover { transform: translateX(6px); }
    .rel-date { transition: background-color .3s, color .3s, transform .35s cubic-bezier(.34,1.56,.64,1); }
    .rel-item:hover .rel-date { transform: rotate(-6deg) scale(1.06); }
    .arrow-slide i { transition: transform .3s; }
    .arrow-slide:hover i { transform: translateX(4px); }

    /* Status chip */
    @@keyframes beat { 0%,100% { box-shadow: 0 0 0 0 rgba(245,158,11,.55); } 60% { box-shadow: 0 0 0 8px rgba(245,158,11,0); } }
    .chip-today { animation: beat 2s infinite; }

    /* Toast */
    #toast { transform: translate(-50%, 24px); opacity: 0; transition: transform .4s cubic-bezier(.34,1.56,.64,1), opacity .3s; pointer-events: none; }
    #toast.show { transform: translate(-50%, 0); opacity: 1; }

    /* Reading progress */
    #read-progress { transform-origin: left; transform: scaleX(0); }

    @@media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .001ms !important; transition-duration: .001ms !important; }
        .reveal-ready .reveal { opacity: 1; transform: none; }
        html { scroll-behavior: auto; }
    }
</style>
<script>document.documentElement.classList.add('reveal-ready');</script>

@php
    $daysLeft = (int) round(now()->startOfDay()->diffInDays($activity->date->copy()->startOfDay(), false));

    // Google Calendar link (WIB -> UTC)
    $calUrl = null;
    try {
        $startAt = \Carbon\Carbon::parse($activity->date->format('Y-m-d') . ' ' . $activity->time_start, 'Asia/Jakarta');
        $endAt = $activity->time_end
            ? \Carbon\Carbon::parse($activity->date->format('Y-m-d') . ' ' . $activity->time_end, 'Asia/Jakarta')
            : $startAt->copy()->addHours(2);
        if ($endAt->lessThanOrEqualTo($startAt)) { $endAt = $startAt->copy()->addHours(2); }
        $calUrl = 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => $activity->title,
            'dates' => $startAt->copy()->utc()->format('Ymd\THis\Z') . '/' . $endAt->copy()->utc()->format('Ymd\THis\Z'),
            'details' => Str::limit(strip_tags($activity->description), 300),
            'location' => $activity->location . ' - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor',
        ]);
    } catch (\Throwable $e) {
        $calUrl = null;
    }
@endphp

<!-- Reading progress -->
<div class="fixed top-0 left-0 right-0 h-1 z-[60] pointer-events-none">
    <div id="read-progress" class="h-full bg-gradient-to-r from-amber-400 to-emerald-500"></div>
</div>

<!-- Breadcrumbs -->
<div class="bg-slate-100/80 border-b border-slate-200/60 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2 min-w-0">
        <a href="{{ route('home') }}" class="crumb flex-shrink-0"><i class="fa-solid fa-house text-[10px] mr-1"></i>Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px] text-slate-300 flex-shrink-0"></i>
        <a href="{{ route('activities.index') }}" class="crumb flex-shrink-0">Kegiatan</a>
        <i class="fa-solid fa-chevron-right text-[8px] text-slate-300 flex-shrink-0"></i>
        <span class="text-slate-800 font-semibold truncate">{{ $activity->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <a href="{{ route('activities.index') }}" class="rise arrow-slide group inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-emerald-700 mb-5 transition-colors" style="--d:0ms">
        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke daftar kegiatan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">
        <!-- Main Content (2 Cols) -->
        <div class="lg:col-span-2">
            <article id="article" class="bg-white rounded-2xl p-5 sm:p-10 border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="rise flex flex-wrap items-center gap-2.5 mb-4" style="--d:60ms">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        {{ $activity->category }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">
                        {{ $activity->date->translatedFormat('l, d F Y') }}
                    </span>
                    @if ($daysLeft === 0)
                        <span class="chip-today px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-400 text-emerald-950">Berlangsung hari ini</span>
                    @elseif ($daysLeft === 1)
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800">Besok</span>
                    @elseif ($daysLeft > 1)
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">{{ $daysLeft }} hari lagi</span>
                    @endif
                </div>

                <h1 class="rise text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-5" style="--d:140ms">
                    {{ $activity->title }}
                </h1>

                @if ($activity->ustadz)
                    <div class="rise speaker bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3.5 mb-6" style="--d:220ms">
                        <div class="sp-icon w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-emerald-700 font-bold uppercase tracking-wider">Pemateri / Narasumber</p>
                            <h2 class="text-lg font-bold text-slate-900">{{ $activity->ustadz }}</h2>
                        </div>
                    </div>
                @endif

                @if ($activity->image)
                    <div class="rise rounded-xl overflow-hidden mb-8 shadow-md" style="--d:300ms">
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" class="img-in w-full h-auto object-cover max-h-[480px]">
                    </div>
                @endif

                <!-- Event details -->
                <div class="reveal grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                    <div class="tile bg-slate-50 rounded-xl p-4 border border-slate-200/80" style="--d:0ms">
                        <div class="t-icon w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                        <span class="text-[11px] text-slate-400 block font-bold uppercase tracking-wide">Tanggal</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $activity->date->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="tile bg-slate-50 rounded-xl p-4 border border-slate-200/80" style="--d:80ms">
                        <div class="t-icon w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <span class="text-[11px] text-slate-400 block font-bold uppercase tracking-wide">Waktu</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $activity->time_start }} - {{ $activity->time_end ?? 'Selesai' }} WIB</span>
                    </div>
                    <div class="tile bg-slate-50 rounded-xl p-4 border border-slate-200/80" style="--d:160ms">
                        <div class="t-icon w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <span class="text-[11px] text-slate-400 block font-bold uppercase tracking-wide">Lokasi / Ruangan</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $activity->location }}</span>
                    </div>
                </div>

                <div class="reveal prose prose-slate max-w-none text-slate-700 leading-relaxed text-base sm:text-lg mb-8">
                    {!! nl2br(e($activity->description)) !!}
                </div>

                <!-- Calendar + Share -->
                <div class="reveal pt-6 border-t border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    @if ($calUrl)
                        <a href="{{ $calUrl }}" target="_blank" rel="noopener" class="btn self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 text-xs font-extrabold shadow-sm hover:shadow-lg hover:shadow-amber-400/30">
                            <i class="fa-regular fa-calendar-plus text-sm"></i> Tambahkan ke Kalender
                        </a>
                    @endif
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Bagikan</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($activity->title . ' - ' . url()->current()) }}" target="_blank" rel="noopener" class="btn px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm hover:shadow-lg hover:shadow-emerald-600/30">
                            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                        </a>
                        <button type="button" id="btn-copy" class="btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-2">
                            <i class="fa-regular fa-copy text-sm"></i> <span>Salin Link</span>
                        </button>
                        <button type="button" id="btn-share" class="btn hidden px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold items-center gap-2">
                            <i class="fa-solid fa-share-nodes text-sm"></i> Lainnya
                        </button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar (1 Col) -->
        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            <!-- Other Upcoming Activities -->
            <div class="reveal bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-900 pb-3 border-b border-slate-100 mb-3 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-amber-400"></span> Agenda Mendatang Lainnya
                </h3>
                <div class="space-y-1">
                    @forelse ($relatedActivities as $rel)
                        <a href="{{ route('activities.show', $rel->slug) }}" class="rel-item flex items-start gap-3.5 group p-2 rounded-xl hover:bg-slate-50">
                            <div class="rel-date w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 font-mono flex flex-col items-center justify-center flex-shrink-0 border border-emerald-100 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700">
                                <span class="text-[10px] uppercase font-bold">{{ $rel->date->translatedFormat('M') }}</span>
                                <span class="text-sm font-extrabold">{{ $rel->date->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-emerald-700 line-clamp-2 transition-colors">
                                    {{ $rel->title }}
                                </h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $rel->ustadz ?? $rel->category }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 py-2">Tidak ada agenda lain saat ini.</p>
                    @endforelse
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('activities.index') }}" class="arrow-slide inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                        Lihat Semua Agenda <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Layanan Jamaah DKM -->
            <div class="reveal pattern-bg rounded-2xl p-5 sm:p-6 text-white border border-emerald-700 shadow-md" style="--d:120ms">
                <h3 class="text-lg font-extrabold mb-2 text-white">Butuh Informasi?</h3>
                <p class="text-xs text-emerald-100/90 leading-relaxed mb-4">
                    Pertanyaan seputar kegiatan, infak, konsultasi syariah, atau fasilitas masjid dapat disampaikan langsung ke pengurus DKM.
                </p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}?text={{ urlencode('Assalamu\'alaikum DKM, saya ingin bertanya tentang kegiatan: ' . $activity->title) }}" target="_blank" rel="noopener" class="btn w-full py-2.5 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-bold text-xs flex items-center justify-center gap-2 shadow hover:shadow-lg hover:shadow-amber-400/30">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi WhatsApp DKM
                </a>
            </div>
        </aside>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 left-1/2 z-[70] bg-emerald-900 text-amber-300 text-sm font-semibold px-5 py-3 rounded-xl shadow-2xl border border-emerald-600 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> <span id="toast-text">Tautan berhasil disalin!</span>
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

        // Stagger the tiles inside the details grid
        document.querySelectorAll('.tile').forEach((t) => {
            t.style.transitionDelay = '';
        });

        // Reading progress (based on the article)
        const bar = document.getElementById('read-progress');
        const article = document.getElementById('article');
        let ticking = false;
        function update() {
            const r = article.getBoundingClientRect();
            const total = r.height - window.innerHeight * 0.6;
            const done = Math.min(Math.max(-r.top + window.innerHeight * 0.2, 0), Math.max(total, 1));
            bar.style.transform = `scaleX(${Math.min(done / Math.max(total, 1), 1)})`;
            ticking = false;
        }
        window.addEventListener('scroll', () => { if (!ticking) { requestAnimationFrame(update); ticking = true; } }, { passive: true });
        update();

        // Toast
        const toast = document.getElementById('toast');
        let timer;
        function showToast(msg) {
            document.getElementById('toast-text').textContent = msg;
            toast.classList.add('show');
            clearTimeout(timer);
            timer = setTimeout(() => toast.classList.remove('show'), 2800);
        }

        // Copy link
        const copyBtn = document.getElementById('btn-copy');
        copyBtn.addEventListener('click', () => {
            const url = window.location.href;
            const done = () => {
                showToast('Tautan berhasil disalin!');
                const icon = copyBtn.querySelector('i');
                const label = copyBtn.querySelector('span');
                icon.className = 'fa-solid fa-check text-sm';
                label.textContent = 'Tersalin';
                setTimeout(() => { icon.className = 'fa-regular fa-copy text-sm'; label.textContent = 'Salin Link'; }, 2000);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(done);
            } else {
                const ta = document.createElement('textarea');
                ta.value = url; ta.style.position = 'fixed'; ta.style.opacity = '0';
                document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy'); done(); } catch (e) {}
                ta.remove();
            }
        });

        // Native share sheet (mostly mobile)
        const shareBtn = document.getElementById('btn-share');
        if (navigator.share) {
            shareBtn.classList.remove('hidden');
            shareBtn.classList.add('flex');
            shareBtn.addEventListener('click', () => {
                navigator.share({ title: document.title, url: window.location.href }).catch(() => {});
            });
        }

        // Ripple on buttons
        document.querySelectorAll('.btn').forEach((btn) => {
            btn.addEventListener('pointerdown', (e) => {
                if (reduce) return;
                const r = btn.getBoundingClientRect();
                const size = Math.max(r.width, r.height);
                const dot = document.createElement('span');
                dot.className = 'ripple-dot';
                dot.style.width = dot.style.height = size + 'px';
                dot.style.left = (e.clientX - r.left - size / 2) + 'px';
                dot.style.top = (e.clientY - r.top - size / 2) + 'px';
                btn.appendChild(dot);
                setTimeout(() => dot.remove(), 650);
            });
        });
    })();
</script>
@endpush