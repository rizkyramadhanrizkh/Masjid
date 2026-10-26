<?php

namespace App\Http\Controllers;

use App\Models\FridaySchedule;
use App\Models\MasjidSetting;
use Illuminate\View\View;

class FridayScheduleController extends Controller
{
    public function index(): View
    {
        $setting = MasjidSetting::current();
        $schedules = FridaySchedule::upcoming()->take(8)->get();
        $nextFriday = $schedules->first();

        return view('public.friday-schedule', compact('setting', 'schedules', 'nextFriday'));
    }
}
