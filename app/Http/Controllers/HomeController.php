<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\FridaySchedule;
use App\Models\MasjidSetting;
use App\Models\News;
use App\Services\PrayerTimeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected PrayerTimeService $prayerTimeService
    ) {}

    public function __invoke(): View
    {
        $setting = MasjidSetting::current();
        $prayerSchedule = $this->prayerTimeService->getTodaySchedule($setting);
        $upcomingActivities = Activity::upcoming()->take(3)->get();
        $nextFriday = FridaySchedule::upcoming()->first();
        $latestNews = News::published()->take(3)->get();

        return view('public.home', compact(
            'setting',
            'prayerSchedule',
            'upcomingActivities',
            'nextFriday',
            'latestNews'
        ));
    }
}
