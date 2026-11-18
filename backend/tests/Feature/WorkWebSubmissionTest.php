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
}
