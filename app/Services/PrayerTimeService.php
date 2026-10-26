<?php

namespace App\Services;

use App\Models\MasjidSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrayerTimeService
{
    /**
     * Get today's prayer times for the configured masjid location (Bogor, Kemenag RI).
     *
     * @return array<string, mixed>
     */
    public function getTodaySchedule(?MasjidSetting $setting = null): array
    {
        $setting = $setting ?? MasjidSetting::current();
        $date = Carbon::now('Asia/Jakarta')->format('d-m-Y');
        $cacheKey = "prayer_timings_{$date}_{$setting->latitude}_{$setting->longitude}";

        $timings = Cache::remember($cacheKey, 60 * 60 * 12, function () use ($setting, $date) {
            return $this->fetchFromAladhan($setting, $date);
        });

        // Add Dhuha (~20 mins after Sunrise)
        if (! isset($timings['Dhuha']) && isset($timings['Sunrise'])) {
            $sunriseCarbon = Carbon::createFromFormat('H:i', $timings['Sunrise'], 'Asia/Jakarta');
            $timings['Dhuha'] = $sunriseCarbon->copy()->addMinutes(20)->format('H:i');
        }

        // Add Iqamah times based on masjid settings
        $iqamah = [
            'Fajr' => $this->calculateIqamah($timings['Fajr'] ?? '04:10', $setting->iqamah_subuh ?? 15),
            'Dhuhr' => $this->calculateIqamah($timings['Dhuhr'] ?? '11:40', $setting->iqamah_dzuhur ?? 10),
            'Asr' => $this->calculateIqamah($timings['Asr'] ?? '14:55', $setting->iqamah_ashar ?? 10),
            'Maghrib' => $this->calculateIqamah($timings['Maghrib'] ?? '17:50', $setting->iqamah_maghrib ?? 10),
            'Isha' => $this->calculateIqamah($timings['Isha'] ?? '19:00', $setting->iqamah_isya ?? 10),
        ];

        // Determine current & next prayer
        $nextPrayer = $this->determineNextPrayer($timings);

        return [
            'date' => Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y'),
            'date_hijri' => $timings['hijri_date'] ?? $this->getApproximateHijriDate(),
            'timings' => [
                'Imsak' => $timings['Imsak'] ?? '04:00',
                'Subuh' => $timings['Fajr'] ?? '04:10',
                'Terbit' => $timings['Sunrise'] ?? '05:30',
                'Dhuha' => $timings['Dhuha'] ?? '05:50',
                'Dzuhur' => $timings['Dhuhr'] ?? '11:40',
                'Ashar' => $timings['Asr'] ?? '14:55',
                'Maghrib' => $timings['Maghrib'] ?? '17:50',
                'Isya' => $timings['Isha'] ?? '19:00',
            ],
            'iqamah' => [
                'Subuh' => $iqamah['Fajr'],
                'Dzuhur' => $iqamah['Dhuhr'],
                'Ashar' => $iqamah['Asr'],
                'Maghrib' => $iqamah['Maghrib'],
                'Isya' => $iqamah['Isha'],
            ],
            'delays' => [
                'Subuh' => $setting->iqamah_subuh ?? 15,
                'Dzuhur' => $setting->iqamah_dzuhur ?? 10,
                'Ashar' => $setting->iqamah_ashar ?? 10,
                'Maghrib' => $setting->iqamah_maghrib ?? 10,
                'Isya' => $setting->iqamah_isya ?? 10,
            ],
            'next_prayer' => $nextPrayer,
        ];
    }

    /**
     * Get monthly prayer calendar for Bogor.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMonthlySchedule(int $month, int $year, ?MasjidSetting $setting = null): array
    {
        $setting = $setting ?? MasjidSetting::current();
        $cacheKey = "prayer_monthly_{$year}_{$month}_{$setting->latitude}_{$setting->longitude}";

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($setting, $month, $year) {
            try {
                $response = Http::timeout(8)
                    ->withoutVerifying()
                    ->withHeaders(['User-Agent' => 'DKM-Masjid-AlKautsar/1.0'])
                    ->get("https://api.aladhan.com/v1/calendar/{$year}/{$month}", [
                        'latitude' => $setting->latitude ?: '-6.597148',
                        'longitude' => $setting->longitude ?: '106.806038',
                        'method' => 20, // Kemenag RI
                    ]);

                if ($response->successful()) {
                    $days = $response->json('data', []);
                    $results = [];

                    foreach ($days as $dayData) {
                        $t = $dayData['timings'] ?? [];
                        // Clean timing strings like "04:12 (WIB)"
                        $clean = [];
                        foreach ($t as $k => $v) {
                            $clean[$k] = substr(trim($v), 0, 5);
                        }

                        $readableDate = $dayData['date']['readable'] ?? '';
                        $carbonDate = Carbon::parse($readableDate);

                        $results[] = [
                            'day' => (int) ($dayData['date']['gregorian']['day'] ?? $carbonDate->format('d')),
                            'date_formatted' => $carbonDate->translatedFormat('d M Y'),
                            'day_name' => $carbonDate->translatedFormat('l'),
                            'hijri' => ($dayData['date']['hijri']['day'] ?? '').' '.($dayData['date']['hijri']['month']['en'] ?? '').' '.($dayData['date']['hijri']['year'] ?? ''),
                            'imsak' => $clean['Imsak'] ?? '04:00',
                            'subuh' => $clean['Fajr'] ?? '04:10',
                            'terbit' => $clean['Sunrise'] ?? '05:30',
                            'dzuhur' => $clean['Dhuhr'] ?? '11:40',
                            'ashar' => $clean['Asr'] ?? '14:55',
                            'maghrib' => $clean['Maghrib'] ?? '17:50',
                            'isya' => $clean['Isha'] ?? '19:00',
                        ];
                    }

                    return $results;
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to fetch monthly prayer times from Aladhan: '.$e->getMessage());
            }

            // Fallback generation for the month
            return $this->generateFallbackMonthlySchedule($month, $year);
        });
    }

    /**
     * Fetch daily timings from Aladhan API with Kemenag method.
     */
    protected function fetchFromAladhan(MasjidSetting $setting, string $date): array
    {
        try {
            $response = Http::timeout(6)
                ->withoutVerifying()
                ->withHeaders(['User-Agent' => 'DKM-Masjid-AlKautsar/1.0'])
                ->get("https://api.aladhan.com/v1/timings/{$date}", [
                    'latitude' => $setting->latitude ?: '-6.597148',
                    'longitude' => $setting->longitude ?: '106.806038',
                    'method' => 20, // Kemenag RI
                ]);

            if ($response->successful()) {
                $rawTimings = $response->json('data.timings', []);
                $hijri = $response->json('data.date.hijri');

                $timings = [];
                foreach ($rawTimings as $key => $val) {
                    $timings[$key] = substr(trim($val), 0, 5);
                }

                if ($hijri) {
                    $timings['hijri_date'] = ($hijri['day'] ?? '').' '.($hijri['month']['en'] ?? '').' '.($hijri['year'] ?? '').' H';
                }

                return $timings;
            }
        } catch (\Throwable $e) {
            Log::warning('Aladhan API request failed: '.$e->getMessage());
        }

        return $this->getFallbackBogorTimings();
    }

    /**
     * Fallback standard timings for Bogor, Indonesia.
     */
    protected function getFallbackBogorTimings(): array
    {
        return [
            'Imsak' => '04:00',
            'Fajr' => '04:10',
            'Sunrise' => '05:30',
            'Dhuha' => '05:52',
            'Dhuhr' => '11:40',
            'Asr' => '14:55',
            'Sunset' => '17:48',
            'Maghrib' => '17:48',
            'Isha' => '19:00',
            'hijri_date' => $this->getApproximateHijriDate(),
        ];
    }

    protected function calculateIqamah(string $prayerTime, int $delayMinutes): string
    {
        try {
            $carbon = Carbon::createFromFormat('H:i', $prayerTime, 'Asia/Jakarta');

            return $carbon->addMinutes($delayMinutes)->format('H:i');
        } catch (\Throwable) {
            return $prayerTime;
        }
    }

    /**
     * Determine the next prayer time and countdown remaining in seconds.
     */
    protected function determineNextPrayer(array $timings): array
    {
        $now = Carbon::now('Asia/Jakarta');

        $prayerOrder = [
            ['name' => 'Subuh', 'key' => 'Fajr'],
            ['name' => 'Terbit', 'key' => 'Sunrise'],
            ['name' => 'Dzuhur', 'key' => 'Dhuhr'],
            ['name' => 'Ashar', 'key' => 'Asr'],
            ['name' => 'Maghrib', 'key' => 'Maghrib'],
            ['name' => 'Isya', 'key' => 'Isha'],
        ];

        foreach ($prayerOrder as $p) {
            $timeStr = $timings[$p['key']] ?? null;
            if (! $timeStr) {
                continue;
            }

            $prayerCarbon = Carbon::createFromFormat('H:i', $timeStr, 'Asia/Jakarta');
            $prayerCarbon->setDate($now->year, $now->month, $now->day);

            if ($prayerCarbon->greaterThan($now)) {
                $seconds = $now->diffInSeconds($prayerCarbon);

                return [
                    'name' => $p['name'],
                    'time' => $timeStr,
                    'seconds_remaining' => $seconds,
                    'is_tomorrow' => false,
                ];
            }
        }

        // If all prayers today have passed, next is tomorrow's Subuh
        $subuhTime = $timings['Fajr'] ?? '04:10';
        $tomorrowSubuh = Carbon::createFromFormat('H:i', $subuhTime, 'Asia/Jakarta')->addDay();
        $tomorrowSubuh->setDate($now->copy()->addDay()->year, $now->copy()->addDay()->month, $now->copy()->addDay()->day);

        return [
            'name' => 'Subuh',
            'time' => $subuhTime,
            'seconds_remaining' => $now->diffInSeconds($tomorrowSubuh),
            'is_tomorrow' => true,
        ];
    }

    protected function getApproximateHijriDate(): string
    {
        // Simple approximate Hijri calculation
        $day = Carbon::now('Asia/Jakarta')->day;

        return $day.' Jumadil Awwal 1448 H';
    }

    protected function generateFallbackMonthlySchedule(int $month, int $year): array
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $results = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $c = Carbon::create($year, $month, $d, 0, 0, 0, 'Asia/Jakarta');
            $results[] = [
                'day' => $d,
                'date_formatted' => $c->translatedFormat('d M Y'),
                'day_name' => $c->translatedFormat('l'),
                'hijri' => $d.' Jumadil Awwal 1448',
                'imsak' => '04:00',
                'subuh' => '04:10',
                'terbit' => '05:30',
                'dzuhur' => '11:40',
                'ashar' => '14:55',
                'maghrib' => '17:48',
                'isya' => '19:00',
            ];
        }

        return $results;
    }
}
