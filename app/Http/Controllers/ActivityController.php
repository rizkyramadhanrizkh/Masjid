<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\MasjidSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $setting = MasjidSetting::current();
        $category = $request->input('category');
        $search = $request->input('q');

        $query = Activity::active();

        if ($category && $category !== 'semua') {
            $query->where('category', 'like', "%{$category}%");
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('ustadz', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $upcomingActivities = (clone $query)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->paginate(9)
            ->withQueryString();

        $categories = [
            'semua' => 'Semua Kegiatan',
            'Kajian Rutin' => 'Kajian Rutin',
            'Kajian Tematik' => 'Kajian Tematik',
            'Kajian Muslimah' => 'Kajian Muslimah',
            'Tabligh Akbar' => 'Tabligh Akbar',
            'Kelas Al-Qur\'an' => 'Kelas Al-Qur\'an',
        ];

        return view('public.activities.index', compact(
            'setting',
            'upcomingActivities',
            'category',
            'search',
            'categories'
        ));
    }

    public function show(string $slug): View
    {
        $setting = MasjidSetting::current();
        $activity = Activity::where('slug', $slug)->firstOrFail();
        $relatedActivities = Activity::upcoming()
            ->where('id', '!=', $activity->id)
            ->take(3)
            ->get();

        return view('public.activities.show', compact('setting', 'activity', 'relatedActivities'));
    }
}
