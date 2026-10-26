@extends('layouts.admin')

@section('title', 'Kelola Kegiatan & Kajian')
@section('header_title', 'Manajemen Kegiatan & Kajian DKM')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Kegiatan & Kajian</h2>
            <p class="text-xs text-slate-500 mt-0.5">Jadwal majelis taklim, tabligh akbar, dan pembinaan jamaah.</p>
        </div>
        <a href="{{ route('admin.activities.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Tambah Kegiatan Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.activities.index') }}" method="GET" class="w-full sm:w-80 relative">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari kegiatan / ustadz..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
        </form>

        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.activities.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ !$category ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Semua</a>
            <a href="{{ route('admin.activities.index', ['category' => 'Kajian Rutin']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ $category === 'Kajian Rutin' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Kajian Rutin</a>
            <a href="{{ route('admin.activities.index', ['category' => 'Kajian Tematik']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ $category === 'Kajian Tematik' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Kajian Tematik</a>
            <a href="{{ route('admin.activities.index', ['category' => 'Tabligh Akbar']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ $category === 'Tabligh Akbar' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Tabligh Akbar</a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-6">Kegiatan / Kajian</th>
                        <th class="py-3.5 px-4">Ustadz / Narasumber</th>
                        <th class="py-3.5 px-4">Waktu & Tempat</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($activities as $act)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if ($act->image)
                                        <img src="{{ asset($act->image) }}" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-book-quran text-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.activities.edit', $act) }}" class="font-bold text-slate-900 hover:text-emerald-700 line-clamp-1">
                                            {{ $act->title }}
                                        </a>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $act->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs font-semibold text-slate-800">
                                {{ $act->ustadz ?? '-' }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-600">
                                <span class="font-bold block text-slate-800">{{ $act->date->translatedFormat('d M Y') }}</span>
                                <span>{{ $act->time_start }} - {{ $act->time_end ?? 'Selesai' }} WIB &bull; {{ $act->location }}</span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ $act->category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if ($act->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('activities.show', $act->slug) }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-slate-100" title="Lihat di Web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.activities.edit', $act) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-slate-100" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.activities.destroy', $act) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">
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
                                Belum ada kegiatan atau kajian yang dijadwalkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($activities->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
