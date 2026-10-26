@extends('layouts.admin')

@section('title', 'Pengaturan Masjid & Waktu Iqamah')
@section('header_title', 'Pengaturan Profil Masjid & Operasional')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Pengaturan Masjid & Jadwal Iqamah</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola identitas masjid, koordinat jadwal sholat, jeda waktu iqamah, dan rekening donasi.</p>
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

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm space-y-8">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Identitas Masjid -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-mosque"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Identitas Masjid</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Masjid <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $setting->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div>
                    <label for="tagline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slogan / Tagline</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $setting->tagline) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div>
                    <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota / Wilayah <span class="text-red-500">*</span></label>
                    <input type="text" id="city" name="city" value="{{ old('city', $setting->city) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div>
                    <label for="whatsapp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Layanan Jamaah</label>
                    <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" placeholder="62812xxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor DKM</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $setting->phone) }}" placeholder="0251-xxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap Masjid <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">{{ old('address', $setting->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bagian 2: Koordinat Lokasi & Jadwal Sholat Kemenag -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Koordinat Lokasi (API Aladhan & Peta)</h3>
                    <p class="text-xs text-slate-400">Koordinat astronomis yang digunakan untuk menghitung jadwal waktu sholat otomatis.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude (Lintang) <span class="text-red-500">*</span></label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $setting->latitude) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                    <p class="text-[11px] text-slate-400 mt-1">Default Kota Bogor: -6.597148</p>
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude (Bujur) <span class="text-red-500">*</span></label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $setting->longitude) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                    <p class="text-[11px] text-slate-400 mt-1">Default Kota Bogor: 106.806038</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="maps_embed_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tautan Embed Google Maps (Iframe Src)</label>
                    <textarea id="maps_embed_url" name="maps_embed_url" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">{{ old('maps_embed_url', $setting->maps_embed_url) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Waktu Jeda Iqamah Berjamaah -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Jeda Waktu Iqamah (Menit Setelah Adzan)</h3>
                    <p class="text-xs text-slate-400">Jeda waktu hitung mundur antara adzan dan dimulainya sholat berjamaah.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center">
                    <label for="iqamah_subuh" class="block text-xs font-extrabold text-emerald-900 uppercase mb-1">Subuh</label>
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" id="iqamah_subuh" name="iqamah_subuh" value="{{ old('iqamah_subuh', $setting->iqamah_subuh) }}" min="1" max="60" required class="w-16 py-1.5 text-center font-bold font-mono text-base rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                        <span class="text-xs text-slate-500 font-semibold">mnt</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center">
                    <label for="iqamah_dzuhur" class="block text-xs font-extrabold text-emerald-900 uppercase mb-1">Dzuhur</label>
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" id="iqamah_dzuhur" name="iqamah_dzuhur" value="{{ old('iqamah_dzuhur', $setting->iqamah_dzuhur) }}" min="1" max="60" required class="w-16 py-1.5 text-center font-bold font-mono text-base rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                        <span class="text-xs text-slate-500 font-semibold">mnt</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center">
                    <label for="iqamah_ashar" class="block text-xs font-extrabold text-emerald-900 uppercase mb-1">Ashar</label>
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" id="iqamah_ashar" name="iqamah_ashar" value="{{ old('iqamah_ashar', $setting->iqamah_ashar) }}" min="1" max="60" required class="w-16 py-1.5 text-center font-bold font-mono text-base rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                        <span class="text-xs text-slate-500 font-semibold">mnt</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center">
                    <label for="iqamah_maghrib" class="block text-xs font-extrabold text-emerald-900 uppercase mb-1">Maghrib</label>
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" id="iqamah_maghrib" name="iqamah_maghrib" value="{{ old('iqamah_maghrib', $setting->iqamah_maghrib) }}" min="1" max="60" required class="w-16 py-1.5 text-center font-bold font-mono text-base rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                        <span class="text-xs text-slate-500 font-semibold">mnt</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center col-span-2 sm:col-span-1">
                    <label for="iqamah_isya" class="block text-xs font-extrabold text-emerald-900 uppercase mb-1">Isya</label>
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" id="iqamah_isya" name="iqamah_isya" value="{{ old('iqamah_isya', $setting->iqamah_isya) }}" min="1" max="60" required class="w-16 py-1.5 text-center font-bold font-mono text-base rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                        <span class="text-xs text-slate-500 font-semibold">mnt</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian 4: Rekening Donasi Resmi -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Rekening Donasi & Infaq</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="bank_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Bank</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" placeholder="Bank Syariah Indonesia (BSI)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>

                <div>
                    <label for="bank_account_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Rekening</label>
                    <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $setting->bank_account_number) }}" placeholder="711-xxxx-xxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">
                </div>

                <div>
                    <label for="bank_account_holder" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atas Nama Pemilik</label>
                    <input type="text" id="bank_account_holder" name="bank_account_holder" value="{{ old('bank_account_holder', $setting->bank_account_holder) }}" placeholder="DKM Masjid Al-Kautsar" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                </div>
            </div>
        </div>

        <!-- Bagian 5: Sejarah, Visi, Misi -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-scroll"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Sejarah, Visi, dan Misi</h3>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="history" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sejarah Pendirian Masjid</label>
                    <textarea id="history" name="history" rows="4" class="w-full p-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 leading-relaxed">{{ old('history', $setting->history) }}</textarea>
                </div>

                <div>
                    <label for="vision" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Visi Masjid</label>
                    <textarea id="vision" name="vision" rows="2" class="w-full p-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 leading-relaxed">{{ old('vision', $setting->vision) }}</textarea>
                </div>

                <div>
                    <label for="mission" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Misi Pengurus DKM (Poin-poin)</label>
                    <textarea id="mission" name="mission" rows="4" class="w-full p-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 leading-relaxed">{{ old('mission', $setting->mission) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
            <button type="submit" class="px-8 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm shadow-md hover:shadow-lg transition">
                Simpan Seluruh Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
