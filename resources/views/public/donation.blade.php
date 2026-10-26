@extends('layouts.public')

@section('title', 'Infaq & Donasi - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Salurkan infaq, shadaqah, dan wakaf untuk operasional dan program dakwah Masjid Al-Kautsar Kota Bogor.')

@section('content')
<div class="pattern-bg text-white py-12 border-b-4 border-amber-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-800 text-amber-300 mb-3 border border-emerald-700">
            <i class="fa-solid fa-hand-holding-dollar"></i> Amal Jariyah
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
            Infaq, Sedekah & Wakaf
        </h1>
        <p class="text-base sm:text-lg text-emerald-100/90 mt-2 max-w-2xl mx-auto">
            "Perumpamaan orang yang menginfakkan hartanya di jalan Allah seperti sebutir biji yang menumbuhkan tujuh tangkai..." (QS. Al-Baqarah: 261)
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-3xl mx-auto space-y-8">
        <!-- Rekening BSI Card -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <p class="text-xs uppercase font-extrabold tracking-wider text-emerald-700 mb-1">Rekening Resmi DKM</p>
            <h2 class="text-2xl font-black text-slate-900">{{ $setting->bank_name ?? 'Bank Syariah Indonesia (BSI)' }}</h2>
            
            <div class="my-6 p-6 bg-emerald-50 rounded-2xl border border-emerald-200/80 inline-block w-full max-w-md">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Nomor Rekening</p>
                <p class="text-3xl font-black text-emerald-950 font-mono tracking-wider" id="target-acc">{{ $setting->bank_account_number ?? '711-2233-445' }}</p>
                <p class="text-sm font-semibold text-emerald-800 mt-2">a.n {{ $setting->bank_account_holder ?? 'DKM Masjid Al-Kautsar Bogor' }}</p>
                
                <button type="button" onclick="copyDonasi()" class="mt-4 px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition inline-flex items-center gap-2">
                    <i class="fa-regular fa-copy"></i> Salin Nomor Rekening
                </button>
                <p id="donasi-feedback" class="text-xs text-emerald-700 font-bold mt-2 hidden">
                    <i class="fa-solid fa-check"></i> Berhasil disalin!
                </p>
            </div>

            <p class="text-xs text-slate-500 max-w-lg mx-auto leading-relaxed">
                Konfirmasi transfer atau informasi zakat dan wakaf dapat disampaikan melalui layanan WhatsApp pengurus DKM Masjid Al-Kautsar Bogor.
            </p>
            <div class="mt-6">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp ?? '6281234567890') }}?text={{ urlencode('Assalamu\'alaikum DKM Masjid Al-Kautsar Bogor, saya ingin konfirmasi donasi:') }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold text-sm border border-emerald-200 transition">
                    <i class="fa-brands fa-whatsapp text-lg text-emerald-600"></i> Konfirmasi via WhatsApp
                </a>
            </div>
        </div>

        <!-- Catatan Transparansi Tahap 2 -->
        <div class="bg-amber-50 rounded-3xl p-6 border border-amber-200 text-center">
            <i class="fa-solid fa-shield-halved text-amber-600 text-3xl mb-2"></i>
            <h3 class="text-base font-bold text-amber-950">Transparansi Keuangan Masjid</h3>
            <p class="text-xs text-amber-800/90 mt-1 max-w-xl mx-auto leading-relaxed">
                Fitur laporan arus kas masuk, pengeluaran, saldo bulanan secara interaktif, grafik keuangan, serta progress bar program donasi khusus akan diaktifkan pada <strong>Tahap 2</strong> setelah konfirmasi.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyDonasi() {
        const text = document.getElementById('target-acc').innerText.trim();
        navigator.clipboard.writeText(text).then(function() {
            const el = document.getElementById('donasi-feedback');
            if (el) {
                el.classList.remove('hidden');
                setTimeout(() => el.classList.add('hidden'), 3500);
            }
        });
    }
</script>
@endpush
