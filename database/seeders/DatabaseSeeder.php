<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\DkmMember;
use App\Models\FridaySchedule;
use App\Models\MasjidSetting;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete dummy data for Masjid Al-Kautsar Bogor.
     */
    public function run(): void
    {
        // 1. Users with roles
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@alkautsar.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '081234567890',
            ]
        );

        $adminKonten = User::updateOrCreate(
            ['email' => 'konten@alkautsar.id'],
            [
                'name' => 'Ustadz Fathurrahman (Admin Konten)',
                'password' => Hash::make('password'),
                'role' => 'admin_konten',
                'phone' => '081298765432',
            ]
        );

        $bendahara = User::updateOrCreate(
            ['email' => 'bendahara@alkautsar.id'],
            [
                'name' => 'H. Bambang Suryono (Bendahara)',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
                'phone' => '081345678901',
            ]
        );

        // 2. Masjid Settings
        MasjidSetting::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Masjid Al-Kautsar',
                'tagline' => 'Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor',
                'city' => 'Bogor',
                'address' => 'Jl. Danau Bogor Raya No. 12, Katulampa, Kec. Bogor Timur, Kota Bogor, Jawa Barat 16143',
                'phone' => '0251-8312345',
                'whatsapp' => '6281234567890',
                'email' => 'kontak@masjidalkautsar-bogor.id',
                'latitude' => '-6.597148',
                'longitude' => '106.806038',
                'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15853.51888062955!2d106.806038!3d-6.597148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5e538ef3d39%3A0xb366b45a278940e7!2sBogor%20Timur%2C%20Bogor%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
                'history' => "Masjid Al-Kautsar Bogor didirikan pada tahun 2008 berawal dari kerinduan masyarakat perumahan dan warga sekitar Katulampa, Bogor Timur akan hadirnya pusat peribadatan dan pembinaan keislaman yang representatif.\n\nAtas prakarsa tokoh masyarakat dan wakaf tanah seluas 2.500 m² dari para muhsinin, pembangunan tahap pertama dimulai secara bergotong-royong. Pada tanggal 1 Muharram 1430 H, masjid ini diresmikan dan diberi nama 'Al-Kautsar' yang bermakna 'Nikmat yang Berlimpah', sebagai doa agar masjid ini senantiasa melimpahkan berkah ilmu, kedamaian, dan kasih sayang bagi masyarakat Bogor.\n\nSaat ini Masjid Al-Kautsar mampu menampung hingga 1.500 jamaah di ruang sholat utama dan plaza serbaguna, dilengkapi dengan sarana perpustakaan Islam, ruang konsultasi syariah, serta gedung TPQ.",
                'vision' => 'Menjadi masjid percontohan di Kota Bogor yang makmur, mandiri, dan berdaya guna dalam membina keimanan serta kesejahteraan umat berlandaskan Al-Qur\'an dan As-Sunnah.',
                'mission' => "1. Menyelenggarakan ibadah sholat lima waktu berjamaah dan sholat Jumat yang tertib, bersih, nyaman, dan khusyuk.\n2. Mengembangkan majelis taklim, kajian tafsir Al-Qur'an, dan pendidikan karakter generasi muda.\n3. Mengelola dana umat (ZISWAF dan infak) secara modern, transparan, akuntabel, dan profesional.\n4. Memperkuat jaringan solidaritas sosial melalui santunan yatim, dhuafa, dan tanggap bencana di wilayah Bogor.",
                'iqamah_subuh' => 15,
                'iqamah_dzuhur' => 10,
                'iqamah_ashar' => 10,
                'iqamah_maghrib' => 10,
                'iqamah_isya' => 10,
                'bank_name' => 'Bank Syariah Indonesia (BSI)',
                'bank_account_number' => '711-2233-445',
                'bank_account_holder' => 'DKM Masjid Al-Kautsar Bogor',
            ]
        );

        // 3. DKM Members
        $members = [
            [
                'name' => 'H. Muhammad Ridwan, S.Ag., M.Pd.I',
                'position' => 'Ketua Umum DKM',
                'period' => '2024 - 2029',
                'phone' => '0812-1111-2222',
                'order' => 1,
            ],
            [
                'name' => 'Dr. Ir. H. Ahmad Fauzi, M.Eng',
                'position' => 'Wakil Ketua DKM',
                'period' => '2024 - 2029',
                'phone' => '0812-2222-3333',
                'order' => 2,
            ],
            [
                'name' => 'Ust. Fathurrahman, S.Sos.I',
                'position' => 'Sekretaris Jenderal',
                'period' => '2024 - 2029',
                'phone' => '0812-3333-4444',
                'order' => 3,
            ],
            [
                'name' => 'H. Bambang Suryono, S.E., Ak.',
                'position' => 'Bendahara Umum',
                'period' => '2024 - 2029',
                'phone' => '0812-4444-5555',
                'order' => 4,
            ],
            [
                'name' => 'Ust. Abdullah Gymnastiar, Lc.',
                'position' => 'Koordinator Bidang Dakwah & Ibadah',
                'period' => '2024 - 2029',
                'phone' => '0812-5555-6666',
                'order' => 5,
            ],
            [
                'name' => 'Ir. H. Hendra Gunawan',
                'position' => 'Koordinator Bidang Sarana & Prasarana',
                'period' => '2024 - 2029',
                'phone' => '0812-6666-7777',
                'order' => 6,
            ],
            [
                'name' => 'H. Dedi Kurniawan, S.E.',
                'position' => 'Koordinator Bidang ZISWAF & Kesejahteraan Umat',
                'period' => '2024 - 2029',
                'phone' => '0812-7777-8888',
                'order' => 7,
            ],
            [
                'name' => 'Rahmat Hidayat, S.Kom',
                'position' => 'Koordinator Bidang Kepemudaan (Remas)',
                'period' => '2024 - 2029',
                'phone' => '0812-8888-9999',
                'order' => 8,
            ],
        ];

        foreach ($members as $m) {
            DkmMember::updateOrCreate(
                ['name' => $m['name'], 'position' => $m['position']],
                $m
            );
        }

        // 4. Activities & Kajian (Realistic upcoming events)
        $today = Carbon::today('Asia/Jakarta');
        $activities = [
            [
                'title' => 'Kajian Subuh Akbar: Meraih Manisnya Iman di Tengah Hiruk Pikuk Dunia',
                'slug' => 'kajian-subuh-akbar-meraih-manisnya-iman',
                'ustadz' => 'Ust. Hilman Fauzi',
                'category' => 'Kajian Rutin Subuh',
                'date' => $today->copy()->addDays(2)->toDateString(),
                'time_start' => '05:00',
                'time_end' => '06:30',
                'location' => 'Ruang Utama Masjid Al-Kautsar Bogor',
                'description' => 'Kajian bakda sholat Subuh berjamaah yang mengupas kiat-kiat menjaga keikhlasan hati dan ketenangan jiwa di era serba cepat. Dilanjutkan dengan sarapan pagi bersama jamaah.',
                'is_active' => true,
            ],
            [
                'title' => 'Kajian Fiqih Kontemporer: Muamalah & Pengelolaan Harta Syariah',
                'slug' => 'kajian-fiqih-kontemporer-muamalah-syariah',
                'ustadz' => 'Dr. Ust. Oni Sahroni, M.A.',
                'category' => 'Kajian Tematik',
                'date' => $today->copy()->addDays(5)->toDateString(),
                'time_start' => '09:00',
                'time_end' => '11:30',
                'location' => 'Aula Serbaguna Lantai 2',
                'description' => 'Membahas hukum akad digital, paylater, asuransi, serta investasi syariah sesuai fatwa DSN-MUI. Terbuka sesi tanya jawab interaktif untuk jamaah.',
                'is_active' => true,
            ],
            [
                'title' => 'Kajian Muslimah: Menjadi Penyejuk Keluarga di Era Modern',
                'slug' => 'kajian-muslimah-penyejuk-keluarga',
                'ustadz' => 'Ustadzah Halimah Alaydrus',
                'category' => 'Kajian Muslimah',
                'date' => $today->copy()->addDays(8)->toDateString(),
                'time_start' => '08:30',
                'time_end' => '11:00',
                'location' => 'Ruang Utama Masjid Al-Kautsar Bogor',
                'description' => 'Kajian khusus muslimah yang membahas keteladanan para ummahatul mukminin dalam mendidik anak dan membangun rumah tangga yang sakinah mawaddah warahmah.',
                'is_active' => true,
            ],
            [
                'title' => 'Tadabbur Al-Qur\'an Surat Al-Kahfi & Buka Puasa Sunnah Senin-Kamis',
                'slug' => 'tadabbur-surat-al-kahfi-buka-puasa-sunnah',
                'ustadz' => 'Ust. Dr. Firanda Andirja, Lc., M.A.',
                'category' => 'Kajian Mingguan',
                'date' => $today->copy()->addDays(11)->toDateString(),
                'time_start' => '16:30',
                'time_end' => '18:15',
                'location' => 'Ruang Utama Masjid Al-Kautsar Bogor',
                'description' => 'Mendalami 4 kisah hikmah dalam Surat Al-Kahfi sebagai benteng akidah akhir zaman. Disediakan hidangan ifthar gratis untuk jamaah puasa sunnah.',
                'is_active' => true,
            ],
            [
                'title' => 'Peringatan Hari Besar Islam: Tabligh Akbar & Santunan 100 Anak Yatim',
                'slug' => 'tabligh-akbar-santunan-anak-yatim',
                'ustadz' => 'KH. Abdullah Gymnastiar (Aa Gym)',
                'category' => 'Tabligh Akbar',
                'date' => $today->copy()->addDays(15)->toDateString(),
                'time_start' => '19:30',
                'time_end' => '22:00',
                'location' => 'Plaza & Ruang Utama Masjid Al-Kautsar',
                'description' => 'Tabligh Akbar akbar bertajuk "Membersihkan Hati Menjemput Ridho Illahi", dirangkaikan dengan penyaluran santunan bagi 100 anak yatim dan dhuafa di wilayah Bogor Timur.',
                'is_active' => true,
            ],
            [
                'title' => 'Bimbingan Tahsin & Tahfidz Al-Qur\'an Metode Talaqqi Dewasa & Lansia',
                'slug' => 'bimbingan-tahsin-tahfidz-metode-talaqqi',
                'ustadz' => 'Ust. M. Rasyid Al-Hafidz',
                'category' => 'Kelas Al-Qur\'an',
                'date' => $today->copy()->addDays(18)->toDateString(),
                'time_start' => '13:30',
                'time_end' => '15:00',
                'location' => 'Ruang Perpustakaan Masjid',
                'description' => 'Kelas perbaikan bacaan makharijul huruf dan tajwid Al-Qur\'an dengan metode yang ramah bagi pemula, dewasa, dan lanjut usia.',
                'is_active' => true,
            ],
        ];

        foreach ($activities as $act) {
            Activity::updateOrCreate(['slug' => $act['slug']], $act);
        }

        // 5. Friday Schedules (4 upcoming Fridays)
        $fridays = [];
        $nextFriday = Carbon::now('Asia/Jakarta')->next(Carbon::FRIDAY);

        $fridayData = [
            [
                'khatib' => 'Prof. Dr. KH. Didin Hafidhuddin, M.Sc.',
                'imam' => 'Ust. Ahmad Fauzan, Al-Hafidz',
                'muadzin' => 'Bilal M. Yusuf',
                'theme' => 'Membangun Ketahanan Keluarga Berbasis Nilai-Nilai Al-Qur\'an di Era Disrupsi',
                'notes' => 'Sholat Jumat dimulai tepat waktu. Jamaah dihimbau datang lebih awal untuk sholat tahiyyatul masjid.',
            ],
            [
                'khatib' => 'Dr. H. Saiful Bahri, M.A.',
                'imam' => 'Ust. M. Rasyid Al-Hafidz',
                'muadzin' => 'Bilal Hendriawan',
                'theme' => 'Menjaga Amanah dan Kejujuran: Fondasi Kesejahteraan Masyarakat',
                'notes' => 'Petugas infak keliling membawa kotak infak operasional masjid.',
            ],
            [
                'khatib' => 'Ust. Dr. H. Amirsyah Tambunan, M.A.',
                'imam' => 'Ust. Ahmad Fauzan, Al-Hafidz',
                'muadzin' => 'Bilal M. Yusuf',
                'theme' => 'Menyuburkan Semangat Berbagi Melalui Zakat, Infak, dan Sedekah',
                'notes' => 'Pemeriksaan tensi darah gratis di posko kesehatan DKM bakda Jumatan.',
            ],
            [
                'khatib' => 'KH. Muhyiddin Junaidi, M.A.',
                'imam' => 'Ust. M. Rasyid Al-Hafidz',
                'muadzin' => 'Bilal Hendriawan',
                'theme' => 'Mempersiapkan Bekal Terbaik Menjemput Keberkahan Hidup',
                'notes' => 'Kultum singkat setelah sholat Jumat tentang persiapan program kebaikan.',
            ],
        ];

        foreach ($fridayData as $i => $fd) {
            $fridayDate = $nextFriday->copy()->addWeeks($i)->toDateString();
            FridaySchedule::updateOrCreate(
                ['date' => $fridayDate],
                array_merge($fd, ['date' => $fridayDate])
            );
        }

        // 6. News & Announcements
        $newsList = [
            [
                'title' => 'Pemberitahuan Pelaksanaan Sholat Gerhana Bulan (Khusuf) Berjamaah di Masjid Al-Kautsar',
                'slug' => 'pelaksanaan-sholat-gerhana-bulan-berjamaah',
                'category' => 'Pengumuman',
                'summary' => 'DKM Masjid Al-Kautsar Bogor mengundang seluruh jamaah untuk melaksanakan sholat gerhana bulan (khusuf) berjamaah dan mendengarkan khutbah gerhana.',
                'content' => "<p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p><p>Sehubungan dengan terjadinya fenomena astronomi Gerhana Bulan Total yang insya Allah dapat teramati dari wilayah Bogor dan sekitarnya, DKM Masjid Al-Kautsar mengajak seluruh kaum muslimin dan muslimat untuk menghidupkan sunnah Rasulullah ﷺ dengan melaksanakan <strong>Sholat Khusuf (Gerhana Bulan) Berjamaah</strong>.</p><p>Adapun rincian kegiatan:</p><ul><li><strong>Hari / Tanggal:</strong> Malam Rabu mendatang</li><li><strong>Waktu:</strong> Pukul 20.00 WIB (setelah Sholat Isya berjamaah)</li><li><strong>Imam:</strong> Ust. Ahmad Fauzan, Al-Hafidz</li><li><strong>Khatib:</strong> Ust. Fathurrahman, S.Sos.I</li><li><strong>Tempat:</strong> Ruang Utama Masjid Al-Kautsar Bogor</li></ul><p>Mari kita perbanyak dzikir, istighfar, takbir, dan sedekah sebagaimana wasiat Baginda Nabi ﷺ ketika terjadi tanda-tanda kebesaran Allah Ta'ala di alam semesta.</p><p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>",
                'published_at' => Carbon::now('Asia/Jakarta')->subDays(1),
                'is_published' => true,
                'views' => 342,
                'author_id' => $adminKonten->id,
            ],
            [
                'title' => 'Pendaftaran Santri Baru TPQ & Rumah Tahfidz Anak Al-Kautsar Tahun Ajaran Baru Dibuka',
                'slug' => 'pendaftaran-santri-baru-tpq-rumah-tahfidz',
                'category' => 'Pengumuman',
                'summary' => 'Penerimaan santri baru untuk jenjang Iqro, Al-Qur\'an, dan Tahfidz Juz 30 telah dibuka. Kuota terbatas 60 santri.',
                'content' => "<p>Alhamdulillah, dalam rangka mencetak generasi Qur'ani yang berakhlak karimah dan cinta masjid, Bidang Pendidikan DKM Masjid Al-Kautsar Bogor kembali membuka pendaftaran santri baru <strong>Taman Pendidikan Al-Qur'an (TPQ) & Rumah Tahfidz Anak</strong>.</p><h4>Program Pembelajaran:</h4><ol><li><strong>Kelas Iqro' (Usia 4-6 tahun):</strong> Pengenalan huruf hijaiyah, doa harian, dan adab islami.</li><li><strong>Kelas Al-Qur'an (Usia 7-12 tahun):</strong> Tartil, kaidah tajwid, dan hafalan surat-surat pendek.</li><li><strong>Kelas Tahfidz Intensif (Usia 8-15 tahun):</strong> Target hafalan Juz 30 dan Juz 29 dengan bimbingan ustadz hafizhahullah.</li></ol><p>Waktu belajar: Senin s/d Kamis pukul 16.00 - 17.30 WIB. Pendaftaran dapat dilakukan langsung di sekretariat DKM setiap hari kerja atau melalui nomor WhatsApp layanan jamaah.</p>",
                'published_at' => Carbon::now('Asia/Jakarta')->subDays(3),
                'is_published' => true,
                'views' => 521,
                'author_id' => $adminKonten->id,
            ],
            [
                'title' => 'Laporan Realisasi Pemasangan Karpet Baru dan Pendingin Udara di Ruang Sholat Utama',
                'slug' => 'laporan-realisasi-karpet-baru-dan-ac',
                'category' => 'Berita',
                'summary' => 'Berkat infak dan wakaf para jamaah, proses penggantian karpet tebal standar Turki dan 4 unit AC inverter 5 PK telah rampung terpasang.',
                'content' => "<p>Segala puji bagi Allah Rabb Semesta Alam. Kami atas nama pengurus DKM Masjid Al-Kautsar Bogor mengucapkan terima kasih (Jazakumullahu Khairan Katsiran) kepada seluruh muhsinin dan jamaah yang telah menginfakkan hartanya dalam program peremajaan sarana masjid.</p><p>Realisasi pekerjaan yang telah diselesaikan meliputi:</p><ul><li>Pemasangan karpet masjid tebal 14mm motif saf hijau tua standar Turki seluas 450 m².</li><li>Pemasangan 4 unit Air Conditioner (AC) Standing Floor Inverter 5 PK untuk menyejukkan shaf depan dan tengah.</li><li>Pembersihan dan sanitasi mendalam seluruh ventilasi serta karpet lama yang dialihkan untuk musholla binaan.</li></ul><p>Semoga setiap sujud dan lantunan ayat suci di atas karpet ini mengalirkan pahala jariyah yang tiada henti bagi para pewakaf dan keluarga. Aamiin ya Rabbal 'Alamin.</p>",
                'published_at' => Carbon::now('Asia/Jakarta')->subDays(6),
                'is_published' => true,
                'views' => 789,
                'author_id' => $superAdmin->id,
            ],
            [
                'title' => 'Kajian Akbar Bersama Ustadz Abdul Somad Sukses Dihadiri 3.000 Jamaah Se-Bogor Raya',
                'slug' => 'kajian-akbar-ustadz-abdul-somad-sukses',
                'category' => 'Berita',
                'summary' => 'Antusiasme ribuan jamaah memadati Masjid Al-Kautsar Bogor dalam gelaran Tabligh Akbar bertema Mengokohkan Ukhuwah Menuju Kejayaan Umat.',
                'content' => '<p>Suasana haru dan penuh kekhidmatan menyelimuti pelataran Masjid Al-Kautsar Bogor saat ribuan jamaah dari berbagai pelosok Kota dan Kabupaten Bogor berkumpul menghadiri Tabligh Akbar bersama <strong>Ustadz Prof. H. Abdul Somad, Lc., D.E.S.A., Ph.D.</strong></p><p>Acara yang dimulai dengan sholat Isya berjamaah tersebut berlangsung tertib dengan dukungan pengamanan dari Babinsa, Bhabinkamtibmas, serta tim kepanduan Remas Al-Kautsar. Dalam tausiyahnya, UAS menekankan pentingnya memakmurkan masjid sebagai pusat peradaban dan menjaga kerukunan antar sesama anak bangsa.</p><p>DKM juga memfasilitasi live streaming melalui kanal YouTube resmi dan mendistribusikan 2.000 botol air mineral dan makanan ringan untuk para jamaah yang hadir.</p>',
                'published_at' => Carbon::now('Asia/Jakarta')->subDays(10),
                'is_published' => true,
                'views' => 1420,
                'author_id' => $adminKonten->id,
            ],
            [
                'title' => 'Program Donasi Renovasi Tempat Wudhu & Toilet Ramah Lansia Resmi Dimulai',
                'slug' => 'program-donasi-renovasi-tempat-wudhu-lansia',
                'category' => 'Berita',
                'summary' => 'Mewujudkan kenyamanan ibadah bagi para sesepuh dan difabel, DKM menginisiasi penataan tempat wudhu duduk dengan pegangan pengaman.',
                'content' => '<p>Berdasarkan aspirasi jamaah lanjut usia dan evaluasi keselamatan, DKM Masjid Al-Kautsar Bogor memulai proyek penyempurnaan area sanitasi masjid yang ramah lansia (elderly-friendly) dan aksesibel bagi penyandang disabilitas.</p><p>Rencana penataan mencakup:</p><ul><li>Pembuatan 8 unit tempat wudhu posisi duduk ergonomis dengan pegangan stainless steel (handrail).</li><li>Pemasangan keramik lantai anti-selip (matte non-slip grade R11) untuk mencegah jamaah terpeleset.</li><li>Ramp landai pengganti anak tangga pada pintu masuk wudhu barat.</li></ul><p>Bagi jamaah yang ingin berpartisipasi dalam amal jariyah ini, infak dapat disalurkan melalui rekening BSI resmi DKM atau kotak infak bertanda khusus di serambi masjid.</p>',
                'published_at' => Carbon::now('Asia/Jakarta')->subDays(14),
                'is_published' => true,
                'views' => 963,
                'author_id' => $superAdmin->id,
            ],
        ];

        foreach ($newsList as $n) {
            News::updateOrCreate(['slug' => $n['slug']], $n);
        }
    }
}
