@extends('layouts.admin')

@section('title', 'Tambah Kegiatan Baru')
@section('header_title', 'Jadwalkan Kegiatan & Kajian Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Formulir Kegiatan / Kajian Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Lengkapi jadwal, tema, dan ustadz pemateri.</p>
        </div>
        <a href="{{ route('admin.activities.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul / Tema Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Kajian Subuh: Meraih Ketenangan Hati" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Ustadz -->
            <div>
                <label for="ustadz" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ustadz / Penceramah</label>
                <input type="text" id="ustadz" name="ustadz" value="{{ old('ustadz') }}" placeholder="Contoh: Ust. Hilman Fauzi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Kegiatan <span class="text-red-500">*</span></label>
                <select id="category" name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                    <option value="Kajian Rutin" {{ old('category') === 'Kajian Rutin' ? 'selected' : '' }}>Kajian Rutin</option>
                    <option value="Kajian Tematik" {{ old('category') === 'Kajian Tematik' ? 'selected' : '' }}>Kajian Tematik</option>
                    <option value="Kajian Muslimah" {{ old('category') === 'Kajian Muslimah' ? 'selected' : '' }}>Kajian Muslimah</option>
                    <option value="Tabligh Akbar" {{ old('category') === 'Tabligh Akbar' ? 'selected' : '' }}>Tabligh Akbar</option>
                    <option value="Kelas Al-Qur'an" {{ old('category') === "Kelas Al-Qur'an" ? 'selected' : '' }}>Kelas Al-Qur'an</option>
                    <option value="Kegiatan Sosial" {{ old('category') === 'Kegiatan Sosial' ? 'selected' : '' }}>Kegiatan Sosial</option>
                </select>
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Time Start & End -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="time_start" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="text" id="time_start" name="time_start" value="{{ old('time_start', '08:00') }}" required placeholder="08:00" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                </div>
                <div>
                    <label for="time_end" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                    <input type="text" id="time_end" name="time_end" value="{{ old('time_end', '10:00') }}" placeholder="10:00" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                </div>
            </div>

            <!-- Location -->
            <div class="md:col-span-2">
                <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi / Ruangan <span class="text-red-500">*</span></label>
                <input type="text" id="location" name="location" value="{{ old('location', 'Ruang Utama Masjid Al-Kautsar Bogor') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi / Penjelasan Kegiatan</label>
            <textarea id="description" name="description" rows="5" placeholder="Penjelasan tema, rincian acara, ketentuan jamaah, dsb..." class="w-full p-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">{{ old('description') }}</textarea>
        </div>

        <!-- Poster Upload with Auto-compress notice -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
            <label for="image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Poster / Flyer Kajian (Opsional)</label>
            <input type="file" id="image" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
            <p class="text-[11px] text-emerald-700 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-compress"></i> Poster otomatis dikompres ke format WebP ringan agar cepat dimuat di HP jamaah.
            </p>
        </div>

        <!-- Active Toggle -->
        <div>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-sm font-bold text-slate-800">Tampilkan di Kalender & Agenda Publik</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.activities.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition">
                Simpan & Jadwalkan Kegiatan
            </button>
        </div>
    </form>
</div>
@endsection
