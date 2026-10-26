@extends('layouts.admin')

@section('title', 'Edit Berita: ' . $news->title)
@section('header_title', 'Edit Warta / Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Perbarui Berita / Pengumuman</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ubah informasi warta yang telah terbit.</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
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

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Berita / Pengumuman <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select id="category" name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                    <option value="Berita" {{ old('category', $news->category) === 'Berita' ? 'selected' : '' }}>Berita Kegiatan</option>
                    <option value="Pengumuman" {{ old('category', $news->category) === 'Pengumuman' ? 'selected' : '' }}>Pengumuman DKM</option>
                    <option value="Artikel" {{ old('category', $news->category) === 'Artikel' ? 'selected' : '' }}>Artikel Keislaman</option>
                </select>
            </div>
        </div>

        <!-- Summary -->
        <div>
            <label for="summary" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ringkasan Singkat</label>
            <textarea id="summary" name="summary" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">{{ old('summary', $news->summary) }}</textarea>
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Isi Berita / Pengumuman Lengkap <span class="text-red-500">*</span></label>
            <textarea id="content" name="content" rows="10" required class="w-full p-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 font-mono">{{ old('content', $news->content) }}</textarea>
        </div>

        <!-- Image Upload -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Saat Ini</label>
            @if ($news->image)
                <div class="mb-4">
                    <img src="{{ asset($news->image) }}" alt="" class="h-32 w-auto rounded-xl object-cover shadow-sm">
                </div>
            @endif
            <label for="image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti Foto Baru (Opsional)</label>
            <input type="file" id="image" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
            <p class="text-[11px] text-emerald-700 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-compress"></i> Foto otomatis dikompres ke format WebP ringan.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center pt-2">
            <div>
                <label for="published_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Waktu Publikasi</label>
                <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
            </div>

            <div class="flex items-center gap-3 pt-6">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-sm font-bold text-slate-800">Tayangkan ke Publik</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
