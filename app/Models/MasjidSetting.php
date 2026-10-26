<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasjidSetting extends Model
{
    protected $guarded = ['id'];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'name' => 'Masjid Al-Kautsar',
            'tagline' => 'Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor',
            'city' => 'Bogor',
            'address' => 'Jl. Danau Bogor Raya, Katulampa, Kec. Bogor Timur, Kota Bogor, Jawa Barat 16143',
            'phone' => '0251-8312345',
            'whatsapp' => '6281234567890',
            'email' => 'kontak@masjidalkautsar-bogor.id',
            'latitude' => '-6.597148',
            'longitude' => '106.806038',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15853.51888062955!2d106.806038!3d-6.597148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5e538ef3d39%3A0xb366b45a278940e7!2sBogor%20Timur%2C%20Bogor%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
            'history' => 'Masjid Al-Kautsar Bogor didirikan sebagai wujud cita-cita bersama warga Muslim di kawasan Bogor Timur untuk menghadirkan pusat ibadah yang makmur, asri, dan menyejukkan hati. Bermula dari musyawarah warga pada tahun 2008, pembangunan dimulai dengan gotong royong dan wakaf tanah dari para muhsinin. Sejak diresmikan, Masjid Al-Kautsar senantiasa berkomitmen menjadi oase spiritual, pusat pembinaan generasi Qur\'ani, dan perekat ukhuwah islamiyah masyarakat Bogor.',
            'vision' => 'Menjadi masjid percontohan yang makmur, mandiri, dan berdaya guna dalam membina keimanan serta kesejahteraan umat berlandaskan Al-Qur\'an dan As-Sunnah.',
            'mission' => "1. Menyelenggarakan ibadah sholat berjamaah lima waktu dan sholat Jumat yang tertib dan nyaman.\n2. Mengembangkan pendidikan Al-Qur'an dan kajian keislaman bagi semua kalangan usia.\n3. Mengelola dana umat (ZISWAF dan infak) secara transparan, akuntabel, dan profesional.\n4. Menjalin ukhuwah islamiyah dan kepedulian sosial bagi masyarakat sekitar.",
            'iqamah_subuh' => 15,
            'iqamah_dzuhur' => 10,
            'iqamah_ashar' => 10,
            'iqamah_maghrib' => 10,
            'iqamah_isya' => 10,
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'bank_account_number' => '711-2233-445',
            'bank_account_holder' => 'DKM Masjid Al-Kautsar Bogor',
        ]);
    }
}
