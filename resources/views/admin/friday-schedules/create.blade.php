@extends('layouts.admin')

@section('title', 'Tambah Jadwal Khutbah Jumat')
@section('header_title', 'Jadwalkan Petugas Sholat Jumat')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Formulir Petugas Khutbah Jumat</h2>
            <p class="text-xs text-slate-500 mt-0.5">Tentukan khatib, imam, muadzin, dan tema khutbah.</p>
        </div>
        <a href="{{ route('admin.friday-schedules.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
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

    <form action="{{ route('admin.friday-schedules.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Date -->
            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Hari Jumat <span class="text-red-500">*</span></label>
                <input type="date" id="date" name="date" value="{{ old('date', now()->next(\Carbon\Carbon::FRIDAY)->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Khatib -->
            <div>
                <label for="khatib" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Khatib <span class="text-red-500">*</span></label>
                <input type="text" id="khatib" name="khatib" value="{{ old('khatib') }}" required placeholder="Contoh: Prof. Dr. KH. Didin Hafidhuddin, M.Sc." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Imam -->
            <div>
                <label for="imam" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Imam <span class="text-red-500">*</span></label>
                <input type="text" id="imam" name="imam" value="{{ old('imam', 'Ust. Ahmad Fauzan, Al-Hafidz') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Muadzin -->
            <div>
                <label for="muadzin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Muadzin / Bilal</label>
                <input type="text" id="muadzin" name="muadzin" value="{{ old('muadzin', 'Bilal M. Yusuf') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>
        </div>

        <!-- Theme -->
        <div>
            <label for="theme" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul / Tema Khutbah <span class="text-red-500">*</span></label>
            <input type="text" id="theme" name="theme" value="{{ old('theme') }}" required placeholder="Contoh: Membangun Generasi Qur'ani di Era Digital" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
            <textarea id="notes" name="notes" rows="2" placeholder="Catatan khusus, informasi kotak infak, dsb..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">{{ old('notes') }}</textarea>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.friday-schedules.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition">
                Simpan Jadwal Jumat
            </button>
        </div>
    </form>
</div>
@endsection
