@extends('layouts.public')

@section('title', 'Jadwal Sholat & Waktu Iqamah - ' . ($setting->name ?? 'Masjid Al-Kautsar') . ' Bogor')
@section('meta_description', 'Jadwal sholat harian dan bulanan resmi untuk wilayah Kota Bogor berstandar Kemenag RI, dilengkapi jadwal iqamah berjamaah.')

@section('content')
<!-- Header Banner -->
<div class="pattern-bg text-white py-12 border-b-4 border-amber-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-800 text-amber-300 mb-3 border border-emerald-700">
            <i class="fa-solid fa-clock"></i> Waktu Sholat Otomatis
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
            Jadwal Sholat & Waktu Iqamah
        </h1>
        <p class="text-base sm:text-lg text-emerald-100/90 mt-2 max-w-2xl mx-auto">
            Wilayah Kota Bogor &bull; Metode Hisab Kementerian Agama Republik Indonesia (Kemenag RI)
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Today's Schedule Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xl mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Hari Ini</p>
                <h2 class="text-2xl font-extrabold text-slate-900 mt-0.5">
                    {{ $prayerSchedule['date'] }}
                </h2>
                <p class="text-sm text-slate-500 font-medium">
                    {{ $prayerSchedule['date_hijri'] }}
                </p>
            </div>
            <div class="bg-emerald-50 px-4 py-2.5 rounded-2xl border border-emerald-100 text-xs text-emerald-800">
                <span class="font-bold">Koordinat Bogor:</span> Lat {{ $setting->latitude ?? '-6.5971' }}, Long {{ $setting->longitude ?? '106.8060' }}
            </div>
        </div>

        <!-- 8 Daily Timings Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 my-8">
            @php
                $timings = $prayerSchedule['timings'];
                $delays = $prayerSchedule['delays'];
                $iqamahTimes = $prayerSchedule['iqamah'];
            @endphp
            @foreach ($timings as $name => $time)
                <div class="bg-slate-50 hover:bg-emerald-50/50 rounded-2xl p-4 text-center border border-slate-100 transition">
                    <p class="text-xs uppercase font-extrabold text-slate-500 tracking-wider mb-1">{{ $name }}</p>
                    <p class="text-2xl font-black font-mono text-emerald-900 my-1">{{ $time }}</p>
                    @if (isset($iqamahTimes[$name]))
                        <div class="mt-2 pt-2 border-t border-slate-200/80 text-[11px]">
                            <span class="text-slate-500">Iqamah:</span>
                            <span class="font-bold text-emerald-700">{{ $iqamahTimes[$name] }}</span>
                            <span class="block text-[10px] text-slate-400">(+{{ $delays[$name] }} mnt)</span>
                        </div>
                    @else
                        <div class="mt-2 pt-2 border-t border-slate-200/80 text-[10px] text-slate-400">
                            WIB
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Iqamah Guidelines Note -->
        <div class="bg-emerald-50/80 border border-emerald-200/60 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-emerald-700 text-xl mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-bold text-emerald-950">Aturan Jeda Waktu Iqamah Sholat Berjamaah</h3>
                    <p class="text-xs text-emerald-800/90 mt-0.5">
                        Iqamah dikumandangkan setelah adzan dengan jeda: <strong>Subuh: {{ $setting->iqamah_subuh ?? 15 }} menit</strong>, <strong>Dzuhur: {{ $setting->iqamah_dzuhur ?? 10 }} menit</strong>, <strong>Ashar: {{ $setting->iqamah_ashar ?? 10 }} menit</strong>, <strong>Maghrib: {{ $setting->iqamah_maghrib ?? 10 }} menit</strong>, <strong>Isya: {{ $setting->iqamah_isya ?? 10 }} menit</strong>.
                    </p>
                </div>
            </div>
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-white hover:bg-slate-50 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200 shadow-sm flex items-center gap-1.5 flex-shrink-0">
                <i class="fa-solid fa-print"></i> Cetak Jadwal
            </button>
        </div>
    </div>

    <!-- Monthly Timetable Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                    Kalender Sholat Bulanan: {{ $monthName }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Jadwal sholat lengkap selama satu bulan penuh untuk wilayah Kota Bogor dan sekitarnya.
                </p>
            </div>

            <!-- Month & Year Selector Form -->
            <form action="{{ route('prayer.schedule') }}" method="GET" class="flex items-center gap-2">
                <select name="month" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm">
                    Pilih
                </button>
            </form>
        </div>

        <!-- Monthly Table -->
        <div class="overflow-x-auto -mx-6 sm:mx-0">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white font-bold text-center">
                        <th class="py-3 px-3 rounded-l-xl">Tgl</th>
                        <th class="py-3 px-3">Hari</th>
                        <th class="py-3 px-3">Imsak</th>
                        <th class="py-3 px-3 bg-emerald-800">Subuh</th>
                        <th class="py-3 px-3">Terbit</th>
                        <th class="py-3 px-3 bg-emerald-800">Dzuhur</th>
                        <th class="py-3 px-3">Ashar</th>
                        <th class="py-3 px-3 bg-emerald-800">Maghrib</th>
                        <th class="py-3 px-3 rounded-r-xl">Isya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-center">
                    @php
                        $todayDay = (date('n') == $month && date('Y') == $year) ? (int) date('j') : 0;
                    @endphp
                    @foreach ($monthlyTimetable as $row)
                        @php
                            $isTodayRow = ($row['day'] == $todayDay);
                        @endphp
                        <tr class="{{ $isTodayRow ? 'bg-amber-100/70 font-bold text-emerald-950 ring-2 ring-amber-400' : ($loop->even ? 'bg-slate-50/70' : 'bg-white') }} hover:bg-emerald-50 transition">
                            <td class="py-2.5 px-3 font-sans font-bold">
                                {{ $row['day'] }}
                                @if ($isTodayRow)
                                    <span class="ml-1 text-[10px] bg-amber-400 text-emerald-950 px-1.5 py-0.5 rounded-full uppercase">Hari ini</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 font-sans text-slate-600">{{ $row['day_name'] }}</td>
                            <td class="py-2.5 px-3 text-slate-500">{{ $row['imsak'] }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-800 {{ $isTodayRow ? 'bg-amber-200/50' : 'bg-emerald-50/50' }}">{{ $row['subuh'] }}</td>
                            <td class="py-2.5 px-3 text-slate-500">{{ $row['terbit'] }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-800 {{ $isTodayRow ? 'bg-amber-200/50' : 'bg-emerald-50/50' }}">{{ $row['dzuhur'] }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-800">{{ $row['ashar'] }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-800 {{ $isTodayRow ? 'bg-amber-200/50' : 'bg-emerald-50/50' }}">{{ $row['maghrib'] }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-800">{{ $row['isya'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
