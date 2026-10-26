<?php

namespace App\Http\Controllers;

use App\Models\DkmMember;
use App\Models\MasjidSetting;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __invoke(): View
    {
        $setting = MasjidSetting::current();
        $members = DkmMember::orderBy('order', 'asc')->get();

        return view('public.profile', compact('setting', 'members'));
    }
}
