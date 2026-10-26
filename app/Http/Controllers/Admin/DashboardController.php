<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\DkmMember;
use App\Models\FridaySchedule;
use App\Models\MasjidSetting;
use App\Models\News;
use App\Services\PrayerTimeService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected PrayerTimeService $prayerTimeService
    ) {}

    public function __invoke(): View
    {
        $setting = MasjidSetting::current();
        $prayerSchedule = $this->prayerTimeService->getTodaySchedule($setting);

        $counts = [
            'news' => News::count(),
            'activities' => Activity::upcoming()->count(),
            'friday_schedules' => FridaySchedule::upcoming()->count(),
            'dkm_members' => DkmMember::count(),
        ];

        $recentNews = News::latest()->take(5)->get();
        $upcomingActivities = Activity::upcoming()->take(5)->get();
        $nextFriday = FridaySchedule::upcoming()->first();

        return view('admin.dashboard', compact(
            'setting',
            'prayerSchedule',
            'counts',
            'recentNews',
            'upcomingActivities',
            'nextFriday'
        ));
    }
}
