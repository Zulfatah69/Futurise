<?php

namespace Tests\Feature;

use App\Models\Festival;
use App\Models\Karya;
use App\Models\Peserta;
use App\Models\PublikasiKarya;
use App\Models\User;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientSpecComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);
    }

    public function test_campus_institution_is_limited_to_two_active_works_per_festival(): void
    {
        $festival = Festival::aktif()->firstOrFail();

        $user1 = User::factory()->create(['account_status' => 'active']);
        $peserta1 = Peserta::create([
            'user_id'         => $user1->id,
            'nama_lengkap'    => $user1->name,
            'email'           => $user1->email,
            'institusi'       => 'Institut Teknologi Nasional Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '152024001',
        ]);

        $user2 = User::factory()->create(['account_status' => 'active']);
        $peserta2 = Peserta::create([
            'user_id'         => $user2->id,
            'nama_lengkap'    => $user2->name,
            'email'           => $user2->email,
            'institusi'       => '  institut teknologi nasional bandung ',
            'jenis_peserta'   => 'pic',
            'nomor_identitas' => 'PIC-ITENAS-02',
        ]);

        Sanctum::actingAs($user1);
        $this->postJson('/api/v1/works', [
            'festival_id'      => $festival->id,
            'judul'            => 'Karya Kampus Satu',
            'deskripsi'        => 'Deskripsi karya pertama.',
            'masalah'          => 'Masalah manajemen energi gedung.',
            'pengguna_sasaran' => 'Pengelola kampus',
            'solusi'           => 'Sensor pintar real-time.',
            'fitur_utama'      => 'Dashboard anomali dan otomasi.',
            'kategori'         => 'IoT, Robotics & Embedded Systems',
            'teknologi'        => 'ESP32, Laravel',
            'tahap_kesiapan'   => 'Prototipe Teruji',
        ])->assertCreated()
            ->assertJsonPath('data.masalah', 'Masalah manajemen energi gedung.');

        Sanctum::actingAs($user2);
        $this->postJson('/api/v1/works', [
            'festival_id' => $festival->id,
            'judul'       => 'Karya Kampus Dua',
            'deskripsi'   => 'Deskripsi karya kedua.',
            'kategori'    => 'Web & Mobile App Innovation',
            'teknologi'   => 'Next.js, Laravel',
        ])->assertCreated();

        // Karya ke-3 dari kampus yang sama wajib ditolak (maks 2 karya/kampus)
        $this->postJson('/api/v1/works', [
            'festival_id' => $festival->id,
            'judul'       => 'Karya Kampus Tiga',
            'deskripsi'   => 'Seharusnya ditolak batas 2 karya.',
            'kategori'    => 'Web & Mobile App Innovation',
            'teknologi'   => 'Next.js, Laravel',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);

        // Kampus berbeda tetap diizinkan
        $otherCampusUser = User::factory()->create(['account_status' => 'active']);
        Peserta::create([
            'user_id'       => $otherCampusUser->id,
            'nama_lengkap'  => $otherCampusUser->name,
            'email'         => $otherCampusUser->email,
            'institusi'     => 'Universitas Padjadjaran',
            'jenis_peserta' => 'mahasiswa',
        ]);
        Sanctum::actingAs($otherCampusUser);
        $this->postJson('/api/v1/works', [
            'festival_id' => $festival->id,
            'judul'       => 'Karya Kampus Lain',
            'deskripsi'   => 'Deskripsi karya kampus berbeda.',
            'kategori'    => 'Artificial Intelligence & Big Data',
            'teknologi'   => 'Python',
        ])->assertCreated();

        $this->assertEquals(3, Karya::count());
        $this->assertEquals(1, $peserta1->karyasAsLeader()->count());
        $this->assertEquals(1, $peserta2->karyasAsLeader()->count());
    }

    public function test_revision_history_and_curator_notes_are_recorded_on_submit_and_review(): void
    {
        $festival = Festival::aktif()->firstOrFail();

        $owner = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $owner->id,
            'nama_lengkap'  => $owner->name,
            'email'         => $owner->email,
            'institusi'     => 'ITB',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $curator = User::factory()->create(['account_status' => 'active']);
        $curator->assignRole('program-kurasi');

        Sanctum::actingAs($owner);
        $createRes = $this->postJson('/api/v1/works', [
            'festival_id'          => $festival->id,
            'judul'                => 'Sistem Deteksi Kualitas Air',
            'deskripsi'            => 'Pemantau pH dan kekeruhan sungai.',
            'masalah'              => 'Pencemaran air sungai perkotaan sulit dipantau cepat.',
            'solusi'               => 'Node sensor bertenaga surya dengan peringatan dini.',
            'kebutuhan_kolaborasi' => 'Pilot project bersama dinas lingkungan hidup.',
            'kategori'             => 'IoT, Robotics & Embedded Systems',
            'teknologi'            => 'ESP32, LoRa, Next.js',
        ])->assertCreated();

        $karyaId = $createRes->json('data.id');

        $this->postJson("/api/v1/works/{$karyaId}/submit")->assertOk();

        // Kurator meminta revisi dengan catatan
        Sanctum::actingAs($curator);
        $this->postJson("/api/v1/works/{$karyaId}/review", [
            'status'  => 'perlu_revisi',
            'catatan' => 'Lampirkan hasil uji kalibrasi sensor pH.',
        ])->assertOk()
            ->assertJsonPath('data.catatan_kurasi', 'Lampirkan hasil uji kalibrasi sensor pH.');

        // Pemilik memperbarui hasil uji dan mengajukan ulang
        Sanctum::actingAs($owner);
        $this->putJson("/api/v1/works/{$karyaId}", [
            'hasil_uji' => 'Akurasi kalibrasi sensor pH mencapai 98.4% di laboratorium.',
        ])->assertOk();

        $this->postJson("/api/v1/works/{$karyaId}/submit")->assertOk();

        $detailRes = $this->getJson("/api/v1/works/{$karyaId}");
        $detailRes->assertOk()
            ->assertJsonCount(5, 'data.versis')
            ->assertJsonPath('data.hasil_uji', 'Akurasi kalibrasi sensor pH mencapai 98.4% di laboratorium.');

        $this->assertDatabaseHas('karya_versi', [
            'karya_id'    => $karyaId,
            'status_baru' => 'perlu_revisi',
            'catatan'     => 'Lampirkan hasil uji kalibrasi sensor pH.',
        ]);
    }

    public function test_showcase_detail_exposes_spec_fields_without_private_contacts(): void
    {
        $festival = Festival::aktif()->firstOrFail();

        $owner = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'         => $owner->id,
            'nama_lengkap'    => 'Ketua Tim Publik',
            'email'           => 'rahasia.ketua@kampus.ac.id',
            'no_telepon'      => '081299988877',
            'institusi'       => 'Politeknik Negeri Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '221511009',
        ]);

        $karya = Karya::create([
            'festival_id'           => $festival->id,
            'peserta_id'            => $peserta->id,
            'judul'                 => 'Asisten Belajar Bahasa Isyarat AI',
            'deskripsi'             => 'Penerjemah SIBI dua arah real-time.',
            'masalah'               => 'Komunikasi layanan publik bagi teman tuli masih terbatas.',
            'pengguna_sasaran'      => 'Layanan publik dan sekolah inklusi',
            'solusi'                => 'Model pose estimation ringan pada perangkat edge.',
            'fitur_utama'           => 'Terjemahan teks & suara instan',
            'kategori'              => 'Artificial Intelligence & Big Data',
            'teknologi'             => 'MediaPipe, ONNX, Next.js',
            'tahap_kesiapan'        => 'Pilot Kampus',
            'hasil_uji'             => 'Skor akurasi 94% pada 300 sampel.',
            'keterbatasan'          => 'Membutuhkan pencahayaan minimal 150 lux.',
            'kebutuhan_kolaborasi'  => 'Mitra integrasi layanan rumah sakit.',
            'status'                => 'lolos',
            'persetujuan_publikasi' => true,
        ]);
        $karya->pesertas()->attach($peserta->id, ['peran' => 'Ketua Tim', 'is_ketua' => true, 'urutan' => 1]);
        $karya->anggotaTims()->create([
            'nama_lengkap' => 'Anggota Rahasia Kontak',
            'peran'        => 'ML Engineer',
            'email'        => 'privat.anggota@kampus.ac.id',
            'no_telepon'   => '081377766655',
            'is_ketua'     => false,
            'urutan'       => 2,
        ]);

        PublikasiKarya::create([
            'karya_id'       => $karya->id,
            'kanal'          => 'website',
            'slug'           => 'asisten-belajar-sibi-ai',
            'judul_publik'   => 'Asisten Belajar Bahasa Isyarat AI',
            'status'         => 'terbit',
            'izin_peserta'   => true,
            'is_unggulan'    => true,
            'tanggal_terbit' => now()->subMinute(),
        ]);

        $detailRes = $this->getJson('/api/v1/showcase/asisten-belajar-sibi-ai');
        $detailRes->assertOk()
            ->assertJsonPath('data.karya.masalah', 'Komunikasi layanan publik bagi teman tuli masih terbatas.')
            ->assertJsonPath('data.karya.kebutuhan_kolaborasi', 'Mitra integrasi layanan rumah sakit.')
            ->assertJsonCount(2, 'data.karya.tim')
            ->assertJsonMissing(['email' => 'rahasia.ketua@kampus.ac.id'])
            ->assertJsonMissing(['email' => 'privat.anggota@kampus.ac.id'])
            ->assertJsonMissing(['no_telepon' => '081377766655']);
    }

    public function test_api_login_endpoint_enforces_rate_limit(): void
    {
        User::factory()->create([
            'email'          => 'rate.limit@futurise.test',
            'password'       => Hash::make('password123'),
            'account_status' => 'active',
        ]);

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email'    => 'rate.limit@futurise.test',
                'password' => 'salah-password',
            ])->assertStatus(401);
        }

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'rate.limit@futurise.test',
            'password' => 'salah-password',
        ])->assertStatus(429);
    }
}
