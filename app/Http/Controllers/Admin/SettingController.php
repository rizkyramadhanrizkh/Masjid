<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasjidSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = MasjidSetting::current();

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = MasjidSetting::current();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'latitude' => ['required', 'string', 'max:50'],
            'longitude' => ['required', 'string', 'max:50'],
            'maps_embed_url' => ['nullable', 'string'],
            'history' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'iqamah_subuh' => ['required', 'integer', 'min:1', 'max:60'],
            'iqamah_dzuhur' => ['required', 'integer', 'min:1', 'max:60'],
            'iqamah_ashar' => ['required', 'integer', 'min:1', 'max:60'],
            'iqamah_maghrib' => ['required', 'integer', 'min:1', 'max:60'],
            'iqamah_isya' => ['required', 'integer', 'min:1', 'max:60'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_holder' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required' => 'Nama masjid wajib diisi.',
            'city.required' => 'Nama kota wajib diisi.',
            'address.required' => 'Alamat masjid wajib diisi.',
            'latitude.required' => 'Koordinat latitude wajib diisi.',
            'longitude.required' => 'Koordinat longitude wajib diisi.',
            'iqamah_subuh.required' => 'Waktu iqamah Subuh wajib diisi.',
        ]);

        $setting->update($validated);

        // Clear cached prayer timings so new settings/coordinates take effect immediately
        Cache::flush();

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan masjid dan jeda iqamah berhasil diperbarui!');
    }
}
