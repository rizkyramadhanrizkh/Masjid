@extends('layouts.public')

@section('title', $activity->title . ' - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', Str::limit(strip_tags($activity->description), 150))

@section('content')
<!-- Breadcrumbs -->
<div class="bg-slate-100/80 border-b border-slate-200/60 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('activities.index') }}" class="hover:text-emerald-700">Kegiatan</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">{{ $activity->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Content (2 Cols) -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-xl overflow-hidden">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        {{ $activity->category }}
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs text-slate-500 font-medium">
                        {{ $activity->date->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                    {{ $activity->title }}
                </h1>

                @if ($activity->ustadz)
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-3.5 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Pemateri / Narasumber</p>
                            <h2 class="text-lg font-bold text-slate-900">{{ $activity->ustadz }}</h2>
                        </div>
                    </div>
                @endif

                @if ($activity->image)
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-md">
                        <img src="{{ asset($activity->image) }}" alt="{{ $activity->title }}" class="w-full h-auto object-cover max-h-[480px]">
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-base sm:text-lg mb-8">
                    {!! nl2br(e($activity->description)) !!}
                </div>

                <!-- Event Details Metadata Box -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/80 mb-8 space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-calendar-check text-emerald-700 text-lg w-5"></i>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold uppercase">Tanggal Pelaksanaan</span>
                            <span class="font-bold text-slate-800">{{ $activity->date->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-clock text-emerald-700 text-lg w-5"></i>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold uppercase">Waktu</span>
                            <span class="font-bold text-slate-800">Pukul {{ $activity->time_start }} - {{ $activity->time_end ?? 'Selesai' }} WIB</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-location-dot text-emerald-700 text-lg w-5"></i>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold uppercase">Lokasi / Ruangan</span>
                            <span class="font-bold text-slate-800">{{ $activity->location }}</span>
                        </div>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan Informasi Ini:</p>
                    <div class="flex items-center gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($activity->title . ' - ' . url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm transition">
                            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-2 transition">
                            <i class="fa-regular fa-copy text-sm"></i> Salin Link
                        </button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar (1 Col) -->
        <div class="space-y-8">
            <!-- Sidebar: Other Upcoming Activities -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-md">
                <h3 class="text-lg font-extrabold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Agenda Mendatang Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse ($relatedActivities as $rel)
                        <a href="{{ route('activities.show', $rel->slug) }}" class="flex items-start gap-3.5 group p-2 rounded-2xl hover:bg-slate-50 transition">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 font-mono flex flex-col items-center justify-center flex-shrink-0 border border-emerald-100 group-hover:bg-emerald-700 group-hover:text-white transition">
                                <span class="text-[10px] uppercase font-bold">{{ $rel->date->translatedFormat('M') }}</span>
                                <span class="text-sm font-extrabold">{{ $rel->date->format('d') }}</span>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-emerald-700 line-clamp-2">
                                    {{ $rel->title }}
                                </h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $rel->ustadz ?? $rel->category }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada agenda lain saat ini.</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('activities.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900">
                        Lihat Semua Agenda &rarr;
                    </a>
                </div>
            </div>

            <!-- Sidebar: Layanan Jamaah DKM -->
            <div class="pattern-bg rounded-3xl p-6 text-white border border-emerald-700 shadow-md">
                <h3 class="text-lg font-extrabold mb-2 text-white">Butuh Informasi?</h3>
                <p class="text-xs text-emerald-100/90 leading-relaxed mb-4">
                    Pertanyaan seputar kegiatan, infak, konsultasi syariah, atau fasilitas masjid dapat disampaikan langsung ke pengurus DKM.
                </p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-amber-400 hover:bg-amber-500 text-emerald-950 font-bold text-xs flex items-center justify-center gap-2 shadow transition">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi WhatsApp DKM
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
