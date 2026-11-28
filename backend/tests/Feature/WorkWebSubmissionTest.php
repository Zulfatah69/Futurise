<?php

namespace Tests\Feature;

use App\Models\Festival;
use App\Models\Karya;
use App\Models\Peserta;
use App\Models\User;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkWebSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);
        Storage::fake('public');
    }

    /**
     * Peserta dapat mengajukan karya baru langsung melalui formulir Web Dashboard.
     */
    public function test_registered_peserta_can_submit_karya_via_web_form(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $peserta = Peserta::create([
            'user_id'         => $user->id,
            'nama_lengkap'    => $user->name,
            'email'           => $user->email,
            'institusi'       => 'Institut Teknologi Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '13520001',
        ]);

        $fakePdf = UploadedFile::fake()->create('proposal_inovasi.pdf', 800, 'application/pdf');

        $response = $this->actingAs($user)->post('/karya/simpan', [
            'judul'                 => 'Deteksi Banjir Berbasis IoT & Machine Learning',
            'kategori'              => 'IoT, Robotics & Embedded Systems',
            'teknologi'             => 'ESP32, LoRa, Python, FastAPI',
            'deskripsi'             => 'Sistem sensor telemetri peringatan dini debit air sungai.',
            'url_demo'              => 'https://demo-iot.itb.ac.id',
            'proposal'              => $fakePdf,
            'action_type'           => 'submit',
            'persetujuan_publikasi' => '1',
            'anggota_nama'          => ['Budi Santoso', 'Siti Rahma'],
            'anggota_peran'         => ['Hardware Specialist', 'Data Analyst'],
            'anggota_email'         => ['budi@itb.ac.id', 'siti@itb.ac.id'],
        ]);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('karya', [
            'peserta_id' => $peserta->id,
            'judul'      => 'Deteksi Banjir Berbasis IoT & Machine Learning',
            'status'     => 'diajukan',
        ]);

        $this->assertDatabaseHas('anggota_tim', [
            'nama_lengkap' => 'Budi Santoso',
            'peran'        => 'Hardware Specialist',
        ]);

        $this->assertDatabaseHas('anggota_tim', [
            'nama_lengkap' => 'Siti Rahma',
            'peran'        => 'Data Analyst',
        ]);

        $this->assertDatabaseHas('karya_file', [
            'nama_asli' => 'proposal_inovasi.pdf',
            'jenis'     => 'dokumen',
        ]);
    }

    /**
     * Peserta dapat menyimpan karya sebagai draft di Web Dashboard.
     */
    public function test_peserta_can_save_karya_as_draft_via_web_form(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $peserta = Peserta::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'email'         => $user->email,
            'institusi'     => 'Universitas Padjadjaran',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $response = $this->actingAs($user)->post('/karya/simpan', [
            'judul'       => 'Aplikasi Deteksi Penyakit Tanaman',
            'kategori'    => 'Artificial Intelligence & Big Data',
            'teknologi'   => 'TensorFlow, Flutter',
            'deskripsi'   => 'Aplikasi scanner penyakit daun tanaman berbasis mobile.',
            'action_type' => 'draft',
        ]);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('karya', [
            'peserta_id' => $peserta->id,
            'judul'      => 'Aplikasi Deteksi Penyakit Tanaman',
            'status'     => 'draft',
        ]);
    }

    /**
     * Peserta dapat mengajukan karya secara final dari status draft.
     */
    public function test_peserta_can_final_submit_karya_via_web_action(): void
    {
        $user = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'email'         => $user->email,
            'institusi'     => 'SMKN 1 Cimahi',
            'jenis_peserta' => 'siswa_smk',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Smart Greenhouse Automation',
            'deskripsi'   => 'Otomasi suhu rumah kaca.',
            'kategori'    => 'IoT, Robotics & Embedded Systems',
            'teknologi'   => 'Arduino, C++',
            'status'      => 'draft',
        ]);

        $response = $this->actingAs($user)->post("/karya/{$karya->id}/submit");

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('karya', [
            'id'     => $karya->id,
            'status' => 'diajukan',
        ]);
    }

    /**
     * PIC Kampus dapat mengaktifkan & memperbarui profil kampus, mengedit draft karya,
     * mengelola anggota tim, serta mengajukan publikasi Showcase ketika karya lolos kurasi.
     */
    public function test_pic_kampus_can_manage_profile_work_team_members_and_request_publication(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $picUser = User::factory()->create([
            'name'              => 'Koordinator Polban',
            'email'             => 'koordinator.polban@futurise.test',
            'email_verified_at' => now(),
            'account_type'      => 'pic',
            'account_status'    => 'active',
            'pic_category'      => 'kampus-sekolah',
        ]);
        $picUser->assignRole('pic-kampus');

        $this->actingAs($picUser)->post('/peserta/daftar', [
            'institusi'       => 'Politeknik Negeri Bandung',
            'jenis_peserta'   => 'pic',
            'nomor_identitas' => '0412058801',
            'no_telepon'      => '081234567890',
        ])->assertRedirect('/dashboard');

        $this->assertTrue($picUser->fresh()->hasRole('pic-kampus'));

        $this->actingAs($picUser)->post('/peserta/profil', [
            'nama_lengkap'    => 'Dr. Koordinator Polban',
            'institusi'       => 'Politeknik Negeri Bandung',
            'nomor_identitas' => '0412058801',
            'no_telepon'      => '081299900011',
        ])->assertRedirect('/dashboard?tab=profil');

        $this->assertDatabaseHas('peserta', [
            'user_id'      => $picUser->id,
            'nama_lengkap' => 'Dr. Koordinator Polban',
            'no_telepon'   => '081299900011',
        ]);

        $this->actingAs($picUser)->post('/karya/simpan', [
            'judul'        => 'Platform Analitik Energi Kampus',
            'kategori'     => 'IoT, Robotics & Embedded Systems',
            'teknologi'    => 'ESP32, MQTT, Laravel',
            'deskripsi'    => 'Pemantauan konsumsi listrik gedung kampus secara real-time.',
            'action_type'  => 'draft',
            'redirect_tab' => 'karya',
        ])->assertRedirect('/dashboard?tab=karya');

        $karya = Karya::where('judul', 'Platform Analitik Energi Kampus')->firstOrFail();

        $this->actingAs($picUser)->post("/karya/{$karya->id}/update", [
            'judul'             => 'Platform Analitik Energi Kampus v2',
            'kategori'          => 'IoT, Robotics & Embedded Systems',
            'teknologi'         => 'ESP32, MQTT, TimescaleDB',
            'deskripsi'         => 'Versi diperbarui dengan prediksi beban puncak.',
            'masalah'           => 'Pemborosan beban idle ruang kelas.',
            'solusi'            => 'Deteksi okupansi berbasis sensor pintar.',
            'catatan_perbaikan' => 'Menambahkan detail masalah & solusi.',
            'redirect_tab'      => 'karya',
        ])->assertRedirect('/dashboard?tab=karya');

        $this->assertDatabaseHas('karya', [
            'id'      => $karya->id,
            'judul'   => 'Platform Analitik Energi Kampus v2',
            'masalah' => 'Pemborosan beban idle ruang kelas.',
        ]);

        $this->actingAs($picUser)->post("/karya/{$karya->id}/anggota", [
            'nama_lengkap' => 'Nadia Putri',
            'peran'        => 'Firmware Engineer',
            'email'        => 'nadia@polban.ac.id',
            'redirect_tab' => 'karya',
        ])->assertRedirect('/dashboard?tab=karya');

        $member = $karya->anggotaTims()->where('nama_lengkap', 'Nadia Putri')->firstOrFail();

        $this->actingAs($picUser)->post("/karya/{$karya->id}/anggota/{$member->id}/hapus", [
            'redirect_tab' => 'karya',
        ])->assertRedirect('/dashboard?tab=karya');

        $this->assertDatabaseMissing('anggota_tim', ['id' => $member->id]);

        // Isolasi akses: mahasiswa lain tidak dapat mengedit karya milik Polban
        $otherUser = User::factory()->create(['account_status' => 'active']);
        $otherPeserta = Peserta::create([
            'user_id'       => $otherUser->id,
            'nama_lengkap'  => $otherUser->name,
            'email'         => $otherUser->email,
            'institusi'     => 'Universitas Lain',
            'jenis_peserta' => 'mahasiswa',
        ]);
        $this->actingAs($otherUser)->post("/karya/{$karya->id}/update", [
            'judul'     => 'Percobaan Ubah Karya Tim Lain',
            'kategori'  => 'Web',
            'teknologi' => 'PHP',
            'deskripsi' => 'Tidak sah.',
        ])->assertNotFound();

        // Ketika kurator meloloskan karya, PIC dapat mengajukan publikasi ke Showcase
        $karya->update(['status' => 'lolos']);

        $this->actingAs($picUser)->post("/karya/{$karya->id}/publikasi", [
            'judul_publik'     => 'Energi Pintar Kampus Polban',
            'ringkasan_publik' => 'Ringkasan untuk publik showcase.',
            'izin_peserta'     => '1',
            'redirect_tab'     => 'kurasi',
        ])->assertRedirect('/dashboard?tab=kurasi');

        $this->assertDatabaseHas('publikasi_karya', [
            'karya_id'     => $karya->id,
            'judul_publik' => 'Energi Pintar Kampus Polban',
            'status'       => 'menunggu_persetujuan',
            'izin_peserta' => true,
        ]);
    }
}
