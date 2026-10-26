<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\FridaySchedule;
use App\Models\MasjidSetting;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MasjidPublicFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'api.aladhan.com/*' => Http::response([
                'code' => 200,
                'status' => 'OK',
                'data' => [
                    'timings' => [
                        'Fajr' => '04:10',
                        'Sunrise' => '05:30',
                        'Dhuhr' => '11:40',
                        'Asr' => '14:55',
                        'Maghrib' => '17:50',
                        'Isha' => '19:00',
                        'Imsak' => '04:00',
                    ],
                    'date' => [
                        'hijri' => [
                            'day' => '12',
                            'month' => ['en' => 'Jumada al-awwal'],
                            'year' => '1448',
                        ],
                    ],
                ],
            ]),
        ]);

        MasjidSetting::current();
    }

    public function test_homepage_is_accessible_and_displays_masjid_name_and_prayer_times(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Masjid Al-Kautsar');
        $response->assertSee('Bogor');
        $response->assertSee('Subuh');
        $response->assertSee('Dzuhur');
        $response->assertSee('Maghrib');
    }

    public function test_prayer_schedule_page_displays_daily_and_monthly_calendar(): void
    {
        $response = $this->get(route('prayer.schedule'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Sholat & Waktu Iqamah');
        $response->assertSee('Iqamah');
        $response->assertSee('Kota Bogor');
    }

    public function test_activities_index_and_detail_pages_work(): void
    {
        $activity = Activity::create([
            'title' => 'Kajian Rutin Subuh Akbar Bersama Ust Hilman',
            'slug' => 'kajian-rutin-subuh-akbar',
            'ustadz' => 'Ust. Hilman Fauzi',
            'category' => 'Kajian Rutin',
            'date' => Carbon::tomorrow()->toDateString(),
            'time_start' => '05:00',
            'time_end' => '06:30',
            'location' => 'Ruang Utama Masjid Al-Kautsar',
            'description' => 'Kajian subuh tematik membahas keimanan dan keikhlasan.',
            'is_active' => true,
        ]);

        $indexResponse = $this->get(route('activities.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($activity->title);

        $showResponse = $this->get(route('activities.show', $activity->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($activity->title);
        $showResponse->assertSee($activity->ustadz);
    }

    public function test_friday_schedule_page_displays_lineup(): void
    {
        FridaySchedule::create([
            'date' => Carbon::now()->next(Carbon::FRIDAY)->toDateString(),
            'khatib' => 'Prof. Dr. KH. Didin Hafidhuddin, M.Sc.',
            'imam' => 'Ust. Ahmad Fauzan, Al-Hafidz',
            'muadzin' => 'Bilal M. Yusuf',
            'theme' => 'Membangun Keluarga Sakinah',
            'notes' => 'Harap hadir lebih awal.',
        ]);

        $response = $this->get(route('friday.schedule'));
        $response->assertStatus(200);
        $response->assertSee('Prof. Dr. KH. Didin Hafidhuddin');
        $response->assertSee('Membangun Keluarga Sakinah');
    }

    public function test_news_page_and_detail_reading_view_increment_views(): void
    {
        $user = User::factory()->create();

        $news = News::create([
            'title' => 'Pengumuman Sholat Gerhana Bulan di Masjid Al-Kautsar',
            'slug' => 'pengumuman-sholat-gerhana-bulan',
            'category' => 'Pengumuman',
            'summary' => 'DKM mengundang jamaah untuk sholat gerhana bersama.',
            'content' => '<p>Isi lengkap pengumuman sholat khusuf.</p>',
            'published_at' => now(),
            'is_published' => true,
            'views' => 10,
            'author_id' => $user->id,
        ]);

        $indexResponse = $this->get(route('news.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($news->title);

        $showResponse = $this->get(route('news.show', $news->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($news->title);

        $this->assertEquals(11, $news->fresh()->views);
    }

    public function test_profile_page_displays_history_and_vision(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSeeText('Profil & Struktur DKM Masjid');
        $response->assertSeeText('Visi Masjid Al-Kautsar');
    }
}
