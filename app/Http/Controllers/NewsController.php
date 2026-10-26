<?php

namespace App\Http\Controllers;

use App\Models\MasjidSetting;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $setting = MasjidSetting::current();
        $category = $request->input('category');
        $search = $request->input('q');

        $query = News::published()->with('author');

        if ($category && in_array($category, ['Berita', 'Pengumuman'], true)) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $newsList = $query->paginate(8)->withQueryString();

        return view('public.news.index', compact('setting', 'newsList', 'category', 'search'));
    }

    public function show(string $slug): View
    {
        $setting = MasjidSetting::current();
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->with('author')
            ->firstOrFail();

        // Increment view count safely
        $news->increment('views');

        $recentNews = News::published()
            ->where('id', '!=', $news->id)
            ->take(4)
            ->get();

        return view('public.news.show', compact('setting', 'news', 'recentNews'));
    }
}
