<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masjid_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Masjid Al-Kautsar');
            $table->string('tagline')->nullable()->default('Pusat Ibadah, Dakwah, dan Ukhuwah Jamaah Kota Bogor');
            $table->string('city')->default('Bogor');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('latitude')->default('-6.597148');
            $table->string('longitude')->default('106.806038');
            $table->text('maps_embed_url')->nullable();
            $table->text('history')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->unsignedSmallInteger('iqamah_subuh')->default(15);
            $table->unsignedSmallInteger('iqamah_dzuhur')->default(10);
            $table->unsignedSmallInteger('iqamah_ashar')->default(10);
            $table->unsignedSmallInteger('iqamah_maghrib')->default(10);
            $table->unsignedSmallInteger('iqamah_isya')->default(10);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('qris_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjid_settings');
    }
};
