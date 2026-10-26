@extends('layouts.admin')

@section('title', 'Tambah Pengurus DKM')
@section('header_title', 'Tambah Pengurus Dewan Kemakmuran Masjid')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Formulir Pengurus DKM Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Lengkapi data profil pengurus dan amanah jabatan.</p>
        </div>
        <a href="{{ route('admin.dkm-members.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
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

    <form action="{{ route('admin.dkm-members.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: H. Muhammad Ridwan, S.Ag., M.Pd.I" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan / Amanah <span class="text-red-500">*</span></label>
                <input type="text" id="position" name="position" value="{{ old('position') }}" required placeholder="Contoh: Ketua Umum DKM" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Period -->
            <div>
                <label for="period" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Periode Kepengurusan <span class="text-red-500">*</span></label>
                <input type="text" id="period" name="period" value="{{ old('period', '2024 - 2029') }}" required placeholder="2024 - 2029" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812-xxxx-xxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil (Angka)</label>
                <input type="number" id="order" name="order" value="{{ old('order', 1) }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
            </div>
        </div>

        <!-- Photo -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
            <label for="photo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Pengurus (Opsional)</label>
            <input type="file" id="photo" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
            <p class="text-[11px] text-emerald-700 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-compress"></i> Foto otomatis dikompres ke format WebP ringan.
            </p>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.dkm-members.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition">
                Simpan Pengurus DKM
            </button>
        </div>
    </form>
</div>
@endsection
