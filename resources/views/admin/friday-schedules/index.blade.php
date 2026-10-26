@extends('layouts.admin')

@section('title', 'Kelola Jadwal Khutbah Jumat')
@section('header_title', 'Manajemen Jadwal Khutbah & Petugas Sholat Jumat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Jadwal Khutbah Jumat</h2>
            <p class="text-xs text-slate-500 mt-0.5">Atur jadwal khatib, imam, muadzin, dan tema khutbah tiap pekan.</p>
        </div>
        <a href="{{ route('admin.friday-schedules.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Tambah Jadwal Jumat Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-6">Tanggal Jumat</th>
                        <th class="py-3.5 px-4">Khatib</th>
                        <th class="py-3.5 px-4">Imam</th>
                        <th class="py-3.5 px-4">Muadzin</th>
                        <th class="py-3.5 px-4">Tema Khutbah</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($schedules as $sched)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $sched->date->translatedFormat('d M Y') }}</span>
                                <span class="text-[11px] text-slate-400">{{ $sched->date->translatedFormat('l') }}</span>
                            </td>
                            <td class="py-4 px-4 font-bold text-emerald-800 whitespace-nowrap">
                                {{ $sched->khatib }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-800">
                                {{ $sched->imam }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-500">
                                {{ $sched->muadzin ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold line-clamp-2 max-w-xs">
                                "{{ $sched->theme }}"
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.friday-schedules.edit', $sched) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-slate-100" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.friday-schedules.destroy', $sched) }}" method="POST" onsubmit="return confirm('Hapus jadwal Jumat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-slate-100" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada jadwal khutbah Jumat tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($schedules->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
