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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkSubmissionTest extends TestCase
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
     * User yang belum melengkapi profil peserta ditolak saat mendaftarkan karya.
     */
    public function test_user_without_peserta_profile_cannot_submit_work(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/works', [
            'judul'     => 'Sistem Otomasi Kampus Berkelanjutan',
            'deskripsi' => 'Aplikasi IoT untuk efisiensi energi kampus.',
            'kategori'  => 'IoT, Robotics & Embedded Systems',
            'teknologi' => 'ESP32, MQTT, Laravel, Vue',
        ]);

        $response->assertForbidden()
            ->assertJsonPath('success', false);
    }

    /**
     * Peserta terdaftar dapat mendaftarkan draft karya beserta anggota tim.
     */
    public function test_registered_peserta_can_create_draft_work_with_team(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $peserta = Peserta::create([
            'user_id'         => $user->id,
            'nama_lengkap'    => $user->name,
            'email'           => $user->email,
            'institusi'       => 'Institut Teknologi Nasional Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '152024183',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/works', [
            'judul'       => 'Sistem Otomasi Kampus Berkelanjutan',
            'deskripsi'   => 'Platform monitoring konsumsi daya gedung berbasis IoT dan AI.',
            'kategori'    => 'IoT, Robotics & Embedded Systems',
            'teknologi'   => 'ESP32, Python, PyTorch, Laravel API',
            'url_demo'    => 'https://demo-otomasi.futurise.id',
            'anggota_tim' => [
                [
                    'nama_lengkap' => 'Ahmad Fadhil',
                    'peran'        => 'Hardware Engineer',
                    'email'        => 'fadhil@itenas.ac.id',
                    'institusi'    => 'Institut Teknologi Nasional Bandung',
                ],
                [
                    'nama_lengkap' => 'Rina Kartika',
                    'peran'        => 'UI/UX Designer',
                    'email'        => 'rina@itenas.ac.id',
                    'institusi'    => 'Institut Teknologi Nasional Bandung',
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.judul', 'Sistem Otomasi Kampus Berkelanjutan');

        $karyaId = $response->json('data.id');

        // Pastikan terdaftar di database karya
        $this->assertDatabaseHas('karya', [
            'id'         => $karyaId,
            'peserta_id' => $peserta->id,
            'status'     => 'draft',
        ]);

        // Pastikan ketua tercatat di tabel pivot karya_peserta
        $this->assertDatabaseHas('karya_peserta', [
            'karya_id'   => $karyaId,
            'peserta_id' => $peserta->id,
            'is_ketua'   => true,
        ]);

        // Pastikan anggota tim tercatat di tabel anggota_tim
        $this->assertDatabaseHas('anggota_tim', [
            'karya_id'     => $karyaId,
            'nama_lengkap' => 'Ahmad Fadhil',
        ]);
        $this->assertDatabaseHas('anggota_tim', [
            'karya_id'     => $karyaId,
            'nama_lengkap' => 'Rina Kartika',
        ]);
    }

    /**
     * Peserta dapat mengunggah berkas proposal atau demo ke karyanya.
     */
    public function test_peserta_can_upload_files_to_work(): void
    {
        $user = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'email'         => $user->email,
            'institusi'     => 'Telkom University',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Sistem Deteksi Anomali Jaringan',
            'deskripsi'   => 'Cybersecurity tool.',
            'kategori'    => 'Cybersecurity & Infrastructure',
            'teknologi'   => 'Go, Wireshark, Docker',
            'status'      => 'draft',
        ]);

        Sanctum::actingAs($user);

        $fakeFile = UploadedFile::fake()->create('proposal_karya.pdf', 500, 'application/pdf');

        $response = $this->postJson("/api/v1/works/{$karya->id}/files", [
            'file'       => $fakeFile,
            'jenis'      => 'dokumen',
            'keterangan' => 'Proposal Lengkap Lomba',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama_asli', 'proposal_karya.pdf');

        $this->assertDatabaseHas('karya_file', [
            'karya_id'  => $karya->id,
            'nama_asli' => 'proposal_karya.pdf',
            'jenis'     => 'dokumen',
        ]);
    }

    /**
     * Ketua tim dapat melakukan final submit karya.
     */
    public function test_peserta_can_submit_work(): void
    {
        $user = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'email'         => $user->email,
            'institusi'     => 'SMKN 1 Bandung',
            'jenis_peserta' => 'siswa_smk',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Aplikasi Edukasi Gamifikasi AR',
            'deskripsi'   => 'Media interaktif untuk pembelajaran sains.',
            'kategori'    => 'Game Development & Interactive Media',
            'teknologi'   => 'Unity, C#, Vuforia',
            'status'      => 'draft',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/works/{$karya->id}/submit");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'diajukan');

        $this->assertDatabaseHas('karya', [
            'id'     => $karya->id,
            'status' => 'diajukan',
        ]);
    }

    /**
     * Pengguna lain tidak diizinkan mengubah karya milik tim lain.
     */
    public function test_other_user_cannot_modify_another_team_work(): void
    {
        $user1 = User::factory()->create(['account_status' => 'active']);
        $peserta1 = Peserta::create([
            'user_id'       => $user1->id,
            'nama_lengkap'  => $user1->name,
            'email'         => $user1->email,
            'institusi'     => 'Universitas Padjadjaran',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta1->id,
            'judul'       => 'Smart Waste Classifier',
            'deskripsi'   => 'AI sorting robot.',
            'kategori'    => 'Artificial Intelligence & Big Data',
            'teknologi'   => 'YOLOv8, Raspberry Pi',
            'status'      => 'draft',
        ]);

        // User 2 yang berbeda
        $user2 = User::factory()->create(['account_status' => 'active']);
        Peserta::create([
            'user_id'       => $user2->id,
            'nama_lengkap'  => $user2->name,
            'email'         => $user2->email,
            'institusi'     => 'ITB',
            'jenis_peserta' => 'mahasiswa',
        ]);

        Sanctum::actingAs($user2);

        $response = $this->putJson("/api/v1/works/{$karya->id}", [
            'judul' => 'Judul Dibajak',
        ]);

        $response->assertForbidden();
    }

    /**
     * Pengguna lain tidak dapat melihat detail karya tim lain (dicegah IDOR dengan 404).
     */
    public function test_unauthorized_user_cannot_view_other_users_work(): void
    {
        $user1 = User::factory()->create(['account_status' => 'active']);
        $peserta1 = Peserta::create([
            'user_id'       => $user1->id,
            'nama_lengkap'  => $user1->name,
            'email'         => $user1->email,
            'institusi'     => 'Universitas Padjadjaran',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta1->id,
            'judul'       => 'Smart Waste Classifier',
            'deskripsi'   => 'AI sorting robot.',
            'kategori'    => 'Artificial Intelligence & Big Data',
            'teknologi'   => 'YOLOv8, Raspberry Pi',
            'status'      => 'draft',
        ]);

        // User 2 yang berbeda mencoba mengakses karya milik User 1
        $user2 = User::factory()->create(['account_status' => 'active']);
        Sanctum::actingAs($user2);

        $response = $this->getJson("/api/v1/works/{$karya->id}");
        $response->assertNotFound();

        // Pemilik asli (User 1) bisa melihat
        Sanctum::actingAs($user1);
        $ownerResponse = $this->getJson("/api/v1/works/{$karya->id}");
        $ownerResponse->assertOk()
            ->assertJsonPath('data.id', $karya->id);
    }

    /**
     * File upload menolak berkas dengan format berbahaya / tidak diizinkan.
     */
    public function test_cannot_upload_file_with_disallowed_mime_type(): void
    {
        $user = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $user->name,
            'email'         => $user->email,
            'institusi'     => 'Institut Teknologi Bandung',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Sistem Deteksi Anomali',
            'deskripsi'   => 'Deskripsi karya.',
            'kategori'    => 'Cyber Security',
            'teknologi'   => 'Python',
            'status'      => 'draft',
        ]);

        Sanctum::actingAs($user);

        // Upload file .php atau executable berbahaya
        $maliciousFile = UploadedFile::fake()->create('exploit.php', 100, 'application/x-php');

        $response = $this->postJson("/api/v1/works/{$karya->id}/files", [
            'file'  => $maliciousFile,
            'jenis' => 'dokumen',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }
}
