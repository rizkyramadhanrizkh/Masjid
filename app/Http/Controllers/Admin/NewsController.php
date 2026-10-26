<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\ImageOptimizerService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $imageOptimizer
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('q');
        $category = $request->input('category');

        $query = News::with('author')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $newsList = $query->paginate(10)->withQueryString();

        return view('admin.news.index', compact('newsList', 'search', 'category'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Berita,Pengumuman,Artikel'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'content.required' => 'Isi berita wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->imageOptimizer->optimizeAndStore($request->file('image'), 'news');
        }

        News::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'image' => $imagePath,
            'published_at' => $validated['published_at'] ?? Carbon::now(),
            'is_published' => $request->boolean('is_published', true),
            'author_id' => Auth::id(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Berita,Pengumuman,Artikel'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'content.required' => 'Isi berita wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? $news->published_at,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->hasFile('image')) {
            $updateData['image'] = $this->imageOptimizer->optimizeAndStore($request->file('image'), 'news');
        }

        $news->update($updateData);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
