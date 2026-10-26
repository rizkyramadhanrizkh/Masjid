<?php

namespace Tests\Feature;

use App\Models\MasjidSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MasjidAdminFeatureTest extends TestCase
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
                ],
            ]),
        ]);

        MasjidSetting::current();
    }

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Portal Pengurus DKM');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::create([
            'name' => 'Admin Konten',
            'email' => 'konten@alkautsar.id',
            'password' => Hash::make('password'),
            'role' => 'admin_konten',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'konten@alkautsar.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_konten_can_create_news(): void
    {
        $admin = User::create([
            'name' => 'Admin Konten',
            'email' => 'konten@alkautsar.id',
            'password' => Hash::make('password'),
            'role' => 'admin_konten',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => 'Pengumuman Safari Dakwah Ramadhan Bogor',
            'category' => 'Pengumuman',
            'summary' => 'Jadwal safari dakwah bersama para asatidz.',
            'content' => '<p>Isi safari dakwah Ramadhan lengkap.</p>',
            'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title' => 'Pengumuman Safari Dakwah Ramadhan Bogor',
            'category' => 'Pengumuman',
        ]);
    }

    public function test_admin_konten_can_create_activity(): void
    {
        $admin = User::create([
            'name' => 'Admin Konten',
            'email' => 'konten@alkautsar.id',
            'password' => Hash::make('password'),
            'role' => 'admin_konten',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.activities.store'), [
            'title' => 'Kajian Rutin Ahad Pagi',
            'ustadz' => 'Ust. Dr. Firanda Andirja',
            'category' => 'Kajian Rutin',
            'date' => Carbon::now()->addDays(3)->toDateString(),
            'time_start' => '08:30',
            'time_end' => '11:00',
            'location' => 'Ruang Utama Masjid Al-Kautsar Bogor',
            'description' => 'Membahas bab tauhid dan keikhlasan.',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.activities.index'));
        $this->assertDatabaseHas('activities', [
            'title' => 'Kajian Rutin Ahad Pagi',
            'ustadz' => 'Ust. Dr. Firanda Andirja',
        ]);
    }

    public function test_admin_can_update_settings_and_iqamah_delays(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@alkautsar.id',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'name' => 'Masjid Al-Kautsar Katulampa',
            'tagline' => 'Oase Hati Warga Bogor',
            'city' => 'Bogor',
            'address' => 'Jl. Danau Bogor Raya No. 12, Katulampa, Bogor',
            'phone' => '0251-8312345',
            'whatsapp' => '6281234567890',
            'email' => 'kontak@masjidalkautsar-bogor.id',
            'latitude' => '-6.597148',
            'longitude' => '106.806038',
            'maps_embed_url' => 'https://maps.google.com',
            'history' => 'Sejarah masjid lengkap...',
            'vision' => 'Visi baru...',
            'mission' => 'Misi baru...',
            'iqamah_subuh' => 20,
            'iqamah_dzuhur' => 12,
            'iqamah_ashar' => 12,
            'iqamah_maghrib' => 10,
            'iqamah_isya' => 15,
            'bank_name' => 'Bank Syariah Indonesia',
            'bank_account_number' => '711-2233-445',
            'bank_account_holder' => 'DKM Masjid Al-Kautsar',
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $this->assertDatabaseHas('masjid_settings', [
            'name' => 'Masjid Al-Kautsar Katulampa',
            'iqamah_subuh' => 20,
            'iqamah_isya' => 15,
        ]);
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@alkautsar.id',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
