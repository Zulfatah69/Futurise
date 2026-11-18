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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    protected Festival $festival;
    protected User $pesertaUser;
    protected Peserta $peserta;
    protected Karya $karya;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);

        $this->festival = Festival::first();

        // Akun peserta
        $this->pesertaUser = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $this->pesertaUser->assignRole('tim-mahasiswa');

        $this->peserta = Peserta::create([
            'user_id'         => $this->pesertaUser->id,
            'nama_lengkap'    => $this->pesertaUser->name,
            'email'           => $this->pesertaUser->email,
            'no_telepon'      => '081234567890',
            'institusi'       => 'Institut Teknologi Nasional Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '152021001',
        ]);

        $this->karya = Karya::create([
            'festival_id'           => $this->festival->id,
            'peserta_id'            => $this->peserta->id,
            'judul'                 => 'Platform AI Vision IoT',
            'deskripsi'             => 'Sistem pemantau pintar berbasis visi komputer.',
            'kategori'              => 'AI, Machine Learning & Big Data',
            'teknologi'             => 'Python, OpenCV, Laravel, Vue',
            'status'                => 'lolos',
            'persetujuan_publikasi' => false,
        ]);
        $this->karya->pesertas()->attach($this->peserta->id, ['is_ketua' => true]);

        // Akun admin / panitia
        $this->adminUser = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $this->adminUser->assignRole('super-admin');
    }

    /**
     * Peserta dapat membuat draft publikasi untuk karya miliknya.
     */
    public function test_peserta_can_create_publication_draft(): void
    {
        Sanctum::actingAs($this->pesertaUser);

        $response = $this->postJson('/api/v1/work-publications', [
            'karya_id'         => $this->karya->id,
            'kanal'            => 'website',
            'judul_publik'     => 'AI Vision Smart Platform',
            'ringkasan_publik' => 'Ringkasan inovasi AI untuk kebutuhan industri.',
            'izin_peserta'     => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.judul_publik', 'AI Vision Smart Platform')
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.izin_peserta', true);

        $this->assertDatabaseHas('publikasi_karya', [
            'karya_id'     => $this->karya->id,
            'judul_publik' => 'AI Vision Smart Platform',
            'status'       => 'draft',
        ]);

        $this->assertDatabaseHas('publikasi_log', [
            'status_lama' => null,
            'status_baru' => 'draft',
        ]);
    }

    /**
     * Peserta lain tidak dapat membuat publikasi untuk karya yang bukan miliknya.
     */
    public function test_peserta_cannot_create_publication_for_others_karya(): void
    {
        $otherUser = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $otherUser->assignRole('tim-mahasiswa');
        Peserta::create([
            'user_id'         => $otherUser->id,
            'nama_lengkap'    => 'Other Student',
            'email'           => $otherUser->email,
            'institusi'       => 'Universitas Lain',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '999999',
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson('/api/v1/work-publications', [
            'karya_id'     => $this->karya->id,
            'judul_publik' => 'Pembajakan Karya',
        ]);

        $response->assertForbidden()
            ->assertJsonPath('success', false);
    }

    /**
     * Peserta dapat mengajukan draft publikasi ke kurasi panitia.
     */
    public function test_peserta_can_submit_publication_for_approval(): void
    {
        Sanctum::actingAs($this->pesertaUser);

        $pub = PublikasiKarya::create([
            'karya_id'     => $this->karya->id,
            'kanal'        => 'website',
            'slug'         => 'ai-vision-smart',
            'judul_publik' => 'AI Vision Smart',
            'status'       => 'draft',
            'izin_peserta' => true,
        ]);

        $response = $this->postJson("/api/v1/work-publications/{$pub->id}/submit", [
            'catatan' => 'Mohon dikurasi agar bisa tampil di galeri online.',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'menunggu_persetujuan');

        $this->assertEquals('menunggu_persetujuan', $pub->fresh()->status);
    }

    /**
     * Admin/Panitia dapat menyetujui dan menerbitkan karya ke showcase.
     */
    public function test_admin_can_approve_and_publish_work(): void
    {
        $pub = PublikasiKarya::create([
            'karya_id'     => $this->karya->id,
            'kanal'        => 'website',
            'slug'         => 'ai-vision-smart-approved',
            'judul_publik' => 'AI Vision Smart Approved',
            'status'       => 'menunggu_persetujuan',
            'izin_peserta' => true,
        ]);

        Sanctum::actingAs($this->adminUser);

        // Approve
        $approveRes = $this->postJson("/api/v1/work-publications/{$pub->id}/approve", [
            'catatan' => 'Karya lulus kurasi.',
        ]);

        $approveRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'terbit');

        $this->assertEquals('terbit', $pub->fresh()->status);
        $this->assertTrue($this->karya->fresh()->persetujuan_publikasi);

        // Verify log audit
        $this->assertDatabaseHas('publikasi_log', [
            'publikasi_id' => $pub->id,
            'status_lama'  => 'menunggu_persetujuan',
            'status_baru'  => 'terbit',
            'diubah_oleh'  => $this->adminUser->id,
        ]);
    }

    /**
     * Admin dapat menarik publikasi karya dengan alasan penarikan.
     */
    public function test_admin_can_withdraw_publication_with_reason(): void
    {
        $pub = PublikasiKarya::create([
            'karya_id'       => $this->karya->id,
            'kanal'          => 'website',
            'slug'           => 'ai-vision-to-withdraw',
            'judul_publik'   => 'AI Vision Withdraw',
            'status'         => 'terbit',
            'izin_peserta'   => true,
            'tanggal_terbit' => now(),
        ]);

        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson("/api/v1/work-publications/{$pub->id}/withdraw", [
            'alasan' => 'Ditemukan plagiarisme pada dokumen lampiran.',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'ditarik')
            ->assertJsonPath('data.alasan_penarikan', 'Ditemukan plagiarisme pada dokumen lampiran.');

        $this->assertEquals('ditarik', $pub->fresh()->status);
    }

    /**
     * Karya berstatus draft atau belum disetujui TIDAK BOLEH tampil di showcase publik.
     */
    public function test_unapproved_work_does_not_appear_in_public_showcase(): void
    {
        PublikasiKarya::create([
            'karya_id'     => $this->karya->id,
            'kanal'        => 'website',
            'slug'         => 'ai-vision-hidden-draft',
            'judul_publik' => 'Karya Tersembunyi Masih Draft',
            'status'       => 'draft',
            'izin_peserta' => true,
        ]);

        // Request publik tanpa token auth
        $response = $this->getJson('/api/v1/showcase');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(0, 'data');
    }

    /**
     * Karya terbit tampil di galeri publik dan dapat dilihat serta diapresiasi (like).
     */
    public function test_published_work_appears_in_showcase_and_can_be_viewed_and_liked(): void
    {
        $pub = PublikasiKarya::create([
            'karya_id'       => $this->karya->id,
            'kanal'          => 'website',
            'slug'           => 'ai-vision-public-expo',
            'judul_publik'   => 'Platform AI Vision Expo',
            'status'         => 'terbit',
            'izin_peserta'   => true,
            'tanggal_terbit' => now()->subHour(),
            'is_unggulan'    => true,
        ]);

        // 1. Cek katalog publik
        $catalogRes = $this->getJson('/api/v1/showcase');
        $catalogRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'ai-vision-public-expo')
            ->assertJsonPath('data.0.is_unggulan', true);

        // 2. Akses detail karya via slug (menaikkan jumlah dilihat)
        $detailRes = $this->getJson('/api/v1/showcase/ai-vision-public-expo');
        $detailRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'ai-vision-public-expo');

        $this->assertEquals(1, $pub->fresh()->jumlah_dilihat);

        // 3. Kirim apresiasi / like
        $likeRes = $this->postJson('/api/v1/showcase/ai-vision-public-expo/like');
        $likeRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.jumlah_suka', 1);

        $this->assertEquals(1, $pub->fresh()->jumlah_suka);
    }
}
