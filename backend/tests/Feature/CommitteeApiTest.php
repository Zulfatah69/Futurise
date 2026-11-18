<?php

namespace Tests\Feature;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\Panitia;
use App\Models\PanitiaFestival;
use App\Models\PanitiaTugas;
use App\Models\User;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommitteeApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Festival $festival;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);

        $this->festival = Festival::first();

        $this->adminUser = User::factory()->create([
            'email' => 'admin.panitia@futurise.id',
        ]);
        $this->adminUser->assignRole('super-admin');
    }

    /**
     * Admin dapat membuat data panitia baru dan melihat daftarnya.
     */
    public function test_admin_can_list_and_create_committee(): void
    {
        Sanctum::actingAs($this->adminUser);

        $payload = [
            'nama_lengkap'           => 'Ahmad Fauzan',
            'email'                  => 'fauzan.panitia@itenas.ac.id',
            'no_telepon'             => '081234567890',
            'institusi'              => 'Institut Teknologi Nasional Bandung',
            'jenis'                  => 'relawan',
            'ukuran_kaos'            => 'L',
            'kontak_darurat_nama'    => 'Budi Darurat',
            'kontak_darurat_telepon' => '089876543210',
            'aktif'                  => true,
        ];

        $res = $this->postJson('/api/v1/committee', $payload);

        $res->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama_lengkap', 'Ahmad Fauzan')
            ->assertJsonPath('data.email', 'fauzan.panitia@itenas.ac.id');

        $this->assertDatabaseHas('panitia', [
            'email' => 'fauzan.panitia@itenas.ac.id',
        ]);

        $listRes = $this->getJson('/api/v1/committee?search=Ahmad');
        $listRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }

    /**
     * Admin dapat memperbarui dan menghapus data panitia.
     */
    public function test_admin_can_update_and_delete_committee(): void
    {
        Sanctum::actingAs($this->adminUser);

        $panitia = Panitia::create([
            'nama_lengkap' => 'Siti Rohmah',
            'email'        => 'siti@futurise.id',
            'jenis'        => 'internal',
            'aktif'        => true,
        ]);

        $updateRes = $this->putJson("/api/v1/committee/{$panitia->id}", [
            'nama_lengkap' => 'Siti Rohmah, S.T.',
            'no_telepon'   => '082211334455',
            'jenis'        => 'internal',
        ]);

        $updateRes->assertOk()
            ->assertJsonPath('data.nama_lengkap', 'Siti Rohmah, S.T.')
            ->assertJsonPath('data.no_telepon', '082211334455');

        $deleteRes = $this->deleteJson("/api/v1/committee/{$panitia->id}");
        $deleteRes->assertOk();

        $this->assertDatabaseMissing('panitia', ['id' => $panitia->id]);
    }

    /**
     * Pengelolaan divisi kepanitiaan festival.
     */
    public function test_can_manage_festival_divisions(): void
    {
        Sanctum::actingAs($this->adminUser);

        // Buat divisi
        $res = $this->postJson("/api/v1/festivals/{$this->festival->id}/divisions", [
            'nama'          => 'Divisi Logistik & Booth',
            'deskripsi'     => 'Menangani perlengkapan venue dan booth expo peserta',
            'kuota_anggota' => 10,
            'urutan'        => 1,
        ]);

        $res->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Divisi Logistik & Booth');

        $divisionId = $res->json('data.id');

        // Ambil daftar divisi
        $list = $this->getJson("/api/v1/festivals/{$this->festival->id}/divisions");
        $list->assertOk()
            ->assertJsonCount(1, 'data');

        // Update divisi
        $update = $this->putJson("/api/v1/festivals/{$this->festival->id}/divisions/{$divisionId}", [
            'deskripsi' => 'Deskripsi diperbarui',
        ]);
        $update->assertOk()
            ->assertJsonPath('data.deskripsi', 'Deskripsi diperbarui');
    }

    /**
     * Penugasan panitia ke festival & divisi.
     */
    public function test_can_assign_committee_to_festival_with_division(): void
    {
        Sanctum::actingAs($this->adminUser);

        $panitia = Panitia::create([
            'nama_lengkap' => 'Rian Pratama',
            'email'        => 'rian@futurise.id',
            'jenis'        => 'mahasiswa_magang',
            'aktif'        => true,
        ]);

        $divisi = DivisiPanitia::create([
            'festival_id'   => $this->festival->id,
            'nama'          => 'Divisi Kurasi Karya',
            'kuota_anggota' => 5,
        ]);

        $assignRes = $this->postJson("/api/v1/festivals/{$this->festival->id}/committee/{$panitia->id}/assign", [
            'divisi_id' => $divisi->id,
            'jabatan'   => 'koordinator',
            'status'    => 'aktif',
        ]);

        $assignRes->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.jabatan', 'koordinator')
            ->assertJsonPath('data.status', 'aktif');

        $this->assertDatabaseHas('panitia_festival', [
            'festival_id' => $this->festival->id,
            'panitia_id'  => $panitia->id,
            'divisi_id'   => $divisi->id,
        ]);
    }

    /**
     * Validasi pencegahan penugasan ganda pada festival yang sama.
     */
    public function test_cannot_assign_same_committee_twice_to_same_festival(): void
    {
        Sanctum::actingAs($this->adminUser);

        $panitia = Panitia::create([
            'nama_lengkap' => 'Hendra Wijaya',
            'email'        => 'hendra@futurise.id',
            'jenis'        => 'relawan',
            'aktif'        => true,
        ]);

        PanitiaFestival::create([
            'festival_id' => $this->festival->id,
            'panitia_id'  => $panitia->id,
            'jabatan'     => 'anggota',
            'status'      => 'aktif',
        ]);

        $duplicateRes = $this->postJson("/api/v1/festivals/{$this->festival->id}/committee/{$panitia->id}/assign", [
            'jabatan' => 'koordinator',
        ]);

        $duplicateRes->assertStatus(422)
            ->assertJsonValidationErrors(['panitia_id']);
    }

    /**
     * Penjadwalan tugas shift panitia dan presensi check-in.
     */
    public function test_can_schedule_task_and_perform_check_in_and_complete(): void
    {
        Sanctum::actingAs($this->adminUser);

        $panitia = Panitia::create([
            'nama_lengkap' => 'Dewi Lestari',
            'email'        => 'dewi@futurise.id',
            'jenis'        => 'internal',
            'aktif'        => true,
        ]);

        $assignment = PanitiaFestival::create([
            'festival_id' => $this->festival->id,
            'panitia_id'  => $panitia->id,
            'jabatan'     => 'anggota',
            'status'      => 'aktif',
        ]);

        // Buat jadwal tugas
        $taskRes = $this->postJson('/api/v1/committee-tasks', [
            'panitia_festival_id' => $assignment->id,
            'judul'               => 'Presensi Registrasi Pengunjung Pagi',
            'tanggal'             => '2026-11-20',
            'jam_mulai'           => '08:00',
            'jam_selesai'         => '12:00',
            'catatan'             => 'Standby di meja depan Gedung Serba Guna',
        ]);

        $taskRes->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.judul', 'Presensi Registrasi Pengunjung Pagi');

        $taskId = $taskRes->json('data.id');

        // Check-in kehadiran shift
        $checkInRes = $this->postJson("/api/v1/committee-tasks/{$taskId}/check-in", [
            'catatan' => 'Tiba tepat waktu di gate utama',
        ]);

        $checkInRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'bertugas');

        $this->assertNotNull($checkInRes->json('data.check_in_pada'));

        // Selesaikan shift
        $completeRes = $this->postJson("/api/v1/committee-tasks/{$taskId}/complete", [
            'catatan' => 'Shift selesai dengan lancar',
        ]);

        $completeRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'selesai');
    }

    /**
     * Validasi jam selesai harus lebih akhir dari jam mulai.
     */
    public function test_cannot_schedule_task_with_invalid_hours(): void
    {
        Sanctum::actingAs($this->adminUser);

        $panitia = Panitia::create([
            'nama_lengkap' => 'Bambang',
            'email'        => 'bambang@futurise.id',
            'jenis'        => 'internal',
            'aktif'        => true,
        ]);

        $assignment = PanitiaFestival::create([
            'festival_id' => $this->festival->id,
            'panitia_id'  => $panitia->id,
            'jabatan'     => 'anggota',
            'status'      => 'aktif',
        ]);

        $res = $this->postJson('/api/v1/committee-tasks', [
            'panitia_festival_id' => $assignment->id,
            'judul'               => 'Shift Salah Waktu',
            'tanggal'             => '2026-11-20',
            'jam_mulai'           => '14:00',
            'jam_selesai'         => '10:00',
        ]);

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['jam_selesai']);
    }

    /**
     * User tanpa login atau role unauthorized ditolak aksesnya.
     */
    public function test_unauthorized_user_cannot_access_committee_apis(): void
    {
        // Tanpa token login
        $unauthRes = $this->getJson('/api/v1/committee');
        $unauthRes->assertUnauthorized();

        // User biasa tanpa role admin/panitia
        $regularUser = User::factory()->create();
        Sanctum::actingAs($regularUser);

        $forbiddenRes = $this->getJson('/api/v1/committee');
        $forbiddenRes->assertForbidden();
    }
}
