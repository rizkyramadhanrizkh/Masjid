@extends('layouts.admin')

@section('title', 'Kelola Pengurus DKM')
@section('header_title', 'Struktur Organisasi Pengurus DKM')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Pengurus DKM</h2>
            <p class="text-xs text-slate-500 mt-0.5">Struktur dewan kemakmuran masjid yang tampil di halaman profil publik.</p>
        </div>
        <a href="{{ route('admin.dkm-members.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengurus Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-6">Nama & Foto</th>
                        <th class="py-3.5 px-4">Jabatan / Amanah</th>
                        <th class="py-3.5 px-4">Periode</th>
                        <th class="py-3.5 px-4">Kontak Telepon</th>
                        <th class="py-3.5 px-4">Urutan Tampil</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($members as $member)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 overflow-hidden flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        @if ($member->photo)
                                            <img src="{{ asset($member->photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-user"></i>
                                        @endif
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-bold text-emerald-800 whitespace-nowrap">
                                {{ $member->position }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                                <span class="bg-slate-100 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $member->period }}</span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-500 text-xs">
                                {{ $member->phone ?? '-' }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-600 font-mono text-center">
                                {{ $member->order }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.dkm-members.edit', $member) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-slate-100" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.dkm-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus data pengurus ini?')">
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
                                Belum ada pengurus DKM yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
