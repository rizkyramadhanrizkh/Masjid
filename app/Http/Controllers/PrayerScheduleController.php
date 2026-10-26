<?php

namespace App\Http\Controllers;

use App\Models\MasjidSetting;
use App\Services\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerScheduleController extends Controller
{
    public function __construct(
        protected PrayerTimeService $prayerTimeService
    ) {}

    public function index(Request $request): View
    {
        $setting = MasjidSetting::current();
        $prayerSchedule = $this->prayerTimeService->getTodaySchedule($setting);

        $now = Carbon::now('Asia/Jakarta');
        $month = (int) $request->input('month', $now->month);
        $year = (int) $request->input('year', $now->year);

        // Clamp month between 1 and 12
        if ($month < 1 || $month > 12) {
            $month = $now->month;
        }

        $monthlyTimetable = $this->prayerTimeService->getMonthlySchedule($month, $year, $setting);
        $monthName = Carbon::create($year, $month, 1)->translatedFormat('F Y');

        return view('public.prayer-schedule', compact(
            'setting',
            'prayerSchedule',
            'monthlyTimetable',
            'month',
            'year',
            'monthName'
        ));
    }
}
