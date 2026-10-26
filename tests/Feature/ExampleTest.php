<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
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

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
