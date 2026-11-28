<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->text('masalah')->nullable()->after('deskripsi');
            $table->string('pengguna_sasaran', 255)->nullable()->after('masalah');
            $table->text('solusi')->nullable()->after('pengguna_sasaran');
            $table->text('fitur_utama')->nullable()->after('solusi');
            $table->string('tahap_kesiapan', 100)->nullable()->after('teknologi');
            $table->text('hasil_uji')->nullable()->after('tahap_kesiapan');
            $table->text('keterbatasan')->nullable()->after('hasil_uji');
            $table->text('kebutuhan_kolaborasi')->nullable()->after('keterbatasan');
            $table->text('catatan_kurasi')->nullable()->after('status');
        });

        Schema::create('karya_versi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->unsignedInteger('versi')->default(1);
            $table->foreignId('diubah_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_lama', 30)->nullable();
            $table->string('status_baru', 30);
            $table->text('catatan')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('app_authentication_secret')->nullable()->after('two_factor_confirmed_at');
            $table->boolean('has_email_authentication')->default(false)->after('app_authentication_secret');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'app_authentication_secret',
                'has_email_authentication',
            ]);
        });

        Schema::dropIfExists('karya_versi');

        Schema::table('karya', function (Blueprint $table) {
            $table->dropColumn([
                'masalah',
                'pengguna_sasaran',
                'solusi',
                'fitur_utama',
                'tahap_kesiapan',
                'hasil_uji',
                'keterbatasan',
                'kebutuhan_kolaborasi',
                'catatan_kurasi',
            ]);
        });
    }
};
