@extends('layouts.admin')

@section('title', 'Kelola Berita & Pengumuman')
@section('header_title', 'Manajemen Berita & Pengumuman DKM')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Berita & Pengumuman</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola publikasi warta kegiatan dan pemberitahuan untuk jamaah.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Tambah Berita Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.news.index') }}" method="GET" class="w-full sm:w-80 relative">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul berita..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
        </form>

        <div class="flex items-center gap-2 self-end sm:self-auto">
            <a href="{{ route('admin.news.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !$category ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Semua</a>
            <a href="{{ route('admin.news.index', ['category' => 'Berita']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $category === 'Berita' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Berita</a>
            <a href="{{ route('admin.news.index', ['category' => 'Pengumuman']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $category === 'Pengumuman' ? 'bg-emerald-100 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">Pengumuman</a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-6">Judul Berita</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Tanggal Rilis</th>
                        <th class="py-3.5 px-4">Dibaca</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($newsList as $news)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if ($news->image)
                                        <img src="{{ asset($news->image) }}" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-newspaper text-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.news.edit', $news) }}" class="font-bold text-slate-900 hover:text-emerald-700 line-clamp-1">
                                            {{ $news->title }}
                                        </a>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $news->summary }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $news->category === 'Pengumuman' ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $news->category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if ($news->is_published)
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tayang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-bold">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span> Draf
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $news->published_at ? $news->published_at->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-600 font-mono">
                                {{ $news->views }}x
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-slate-100" title="Lihat di Web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $news) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-slate-100" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $news) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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
                                Belum ada berita atau pengumuman yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($newsList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $newsList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
