<?php

namespace Tests\Feature;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\Karya;
use App\Models\KaryaFile;
use App\Models\Panitia;
use App\Models\PanitiaFestival;
use App\Models\PeranAkses;
use App\Models\Peserta;
use App\Models\User;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\ReferensiInstitusiSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class Dev1CompletionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);
        Storage::fake('public');
    }

    public function test_cors_headers_allow_nextjs_origin_with_credentials(): void
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:3000',
        ])->getJson('/api/v1/festivals/active');

        $response->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->assertHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function test_user_can_request_and_complete_password_reset_via_api(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email'             => 'peserta.reset@futurise.id',
            'password'          => Hash::make('oldpassword123'),
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        $user->createToken('existing-token');

        $forgotRes = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $forgotRes->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($user, ResetPassword::class);

        $token = Password::broker()->createToken($user);

        // Invalid token must fail with 422
        $badReset = $this->postJson('/api/v1/auth/reset-password', [
            'token'                 => 'invalid-token',
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $badReset->assertStatus(422)
            ->assertJsonPath('success', false);

        // Valid token updates password and revokes old Sanctum tokens
        $resetRes = $this->postJson('/api/v1/auth/reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $resetRes->assertOk()
            ->assertJsonPath('success', true);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        $this->assertCount(0, $user->tokens);
    }

    public function test_participant_can_view_and_update_own_profile_with_idor_protection(): void
    {
        $owner = User::factory()->create([
            'name'           => 'Rizky Pratama',
            'email'          => 'rizky@itenas.ac.id',
            'account_status' => 'active',
        ]);
        $peserta = Peserta::create([
            'user_id'         => $owner->id,
            'nama_lengkap'    => $owner->name,
            'email'           => $owner->email,
            'no_telepon'      => '08111111111',
            'institusi'       => 'Institut Teknologi Nasional Bandung',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '152024001',
        ]);

        $otherUser = User::factory()->create(['account_status' => 'active']);

        // Non-owner cannot view (404 anti-IDOR) or update (403)
        Sanctum::actingAs($otherUser);
        $this->getJson("/api/v1/participants/{$peserta->id}")->assertNotFound();
        $this->putJson("/api/v1/participants/{$peserta->id}", [
            'institusi' => 'Kampus Bajakan',
        ])->assertForbidden();

        // Owner can view & update and changes sync to users & people
        Sanctum::actingAs($owner);
        $this->getJson("/api/v1/participants/{$peserta->id}")
            ->assertOk()
            ->assertJsonPath('data.email', 'rizky@itenas.ac.id');

        $updateRes = $this->putJson("/api/v1/participants/{$peserta->id}", [
            'nama_lengkap'    => 'Rizky Pratama Putra',
            'no_telepon'      => '08222222222',
            'nomor_identitas' => '152024999',
        ]);

        $updateRes->assertOk()
            ->assertJsonPath('data.nama_lengkap', 'Rizky Pratama Putra')
            ->assertJsonPath('data.no_telepon', '08222222222');

        $this->assertDatabaseHas('users', [
            'id'   => $owner->id,
            'name' => 'Rizky Pratama Putra',
        ]);
        $this->assertDatabaseHas('people', [
            'user_id'   => $owner->id,
            'full_name' => 'Rizky Pratama Putra',
            'phone'     => '08222222222',
        ]);
    }

    public function test_curator_can_review_submitted_work_and_participant_can_list_team_and_delete_draft_file(): void
    {
        $owner = User::factory()->create(['account_status' => 'active']);
        $peserta = Peserta::create([
            'user_id'       => $owner->id,
            'nama_lengkap'  => $owner->name,
            'email'         => $owner->email,
            'institusi'     => 'ITB',
            'jenis_peserta' => 'mahasiswa',
        ]);

        $festival = Festival::aktif()->first();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Smart Flood Warning',
            'deskripsi'   => 'Sensor banjir berbasis LoRaWAN.',
            'kategori'    => 'IoT, Robotics & Embedded Systems',
            'teknologi'   => 'ESP32, LoRa, Laravel',
            'status'      => 'draft',
        ]);
        $karya->pesertas()->attach($peserta->id, [
            'peran'    => 'Ketua Tim',
            'is_ketua' => true,
            'urutan'   => 1,
        ]);
        $karya->anggotaTims()->create([
            'nama_lengkap'  => 'Siti Aisyah',
            'peran'         => 'Backend Developer',
            'is_ketua'      => false,
            'jenis_peserta' => 'mahasiswa',
            'urutan'        => 2,
        ]);

        Sanctum::actingAs($owner);

        // Team members endpoint returns both registered and offline members
        $teamRes = $this->getJson("/api/v1/works/{$karya->id}/team-members");
        $teamRes->assertOk()
            ->assertJsonCount(1, 'data.peserta_terdaftar')
            ->assertJsonCount(1, 'data.anggota_tim');

        // Upload a primary file and delete it while still draft
        $uploadRes = $this->postJson("/api/v1/works/{$karya->id}/files", [
            'file'  => UploadedFile::fake()->create('proposal.pdf', 120, 'application/pdf'),
            'jenis' => 'utama',
        ]);
        $uploadRes->assertCreated();
        $fileId = $uploadRes->json('data.id');
        $filePath = $uploadRes->json('data.path');

        $karya->refresh();
        $this->assertEquals($filePath, $karya->file_utama);
        Storage::disk('public')->assertExists($filePath);

        $deleteFileRes = $this->deleteJson("/api/v1/works/{$karya->id}/files/{$fileId}");
        $deleteFileRes->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('karya_file', ['id' => $fileId]);
        $this->assertNull($karya->fresh()->file_utama);
        Storage::disk('public')->assertMissing($filePath);

        // Curator cannot review a draft work
        $curator = User::factory()->create(['account_status' => 'active']);
        $curator->assignRole('program-kurasi');

        Sanctum::actingAs($curator);
        $this->postJson("/api/v1/works/{$karya->id}/review", [
            'status' => 'lolos',
        ])->assertStatus(422);

        // Owner submits work, regular participant cannot review (403)
        Sanctum::actingAs($owner);
        $this->postJson("/api/v1/works/{$karya->id}/submit")->assertOk();
        $this->postJson("/api/v1/works/{$karya->id}/review", [
            'status' => 'lolos',
        ])->assertForbidden();

        // Owner cannot delete file once submitted
        $fileAfterSubmit = KaryaFile::create([
            'karya_id'      => $karya->id,
            'jenis'         => 'dokumen',
            'nama_asli'     => 'locked.pdf',
            'path'          => 'karya_files/locked.pdf',
            'diunggah_oleh' => $peserta->id,
        ]);
        $this->deleteJson("/api/v1/works/{$karya->id}/files/{$fileAfterSubmit->id}")
            ->assertStatus(422);

        // Curator lists and reviews work -> perlu_revisi -> lolos
        Sanctum::actingAs($curator);
        $this->getJson('/api/v1/works')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $reviewRes = $this->postJson("/api/v1/works/{$karya->id}/review", [
            'status'  => 'lolos',
            'catatan' => 'Dokumentasi lengkap dan memenuhi syarat lomba.',
        ]);
        $reviewRes->assertOk()
            ->assertJsonPath('data.status', 'lolos')
            ->assertJsonPath('meta.catatan', 'Dokumentasi lengkap dan memenuhi syarat lomba.');
    }

    public function test_admin_can_list_festival_committee_and_manage_access_roles(): void
    {
        $admin = User::factory()->create(['account_status' => 'active']);
        $admin->assignRole('super-admin');

        $festival = Festival::aktif()->first();
        $divisi = DivisiPanitia::create([
            'festival_id' => $festival->id,
            'nama'        => 'Divisi Kurasi',
        ]);
        $panitia = Panitia::create([
            'nama_lengkap' => 'Nadia Putri',
            'email'        => 'nadia@futurise.id',
            'jenis'        => 'internal',
            'aktif'        => true,
        ]);
        PanitiaFestival::create([
            'festival_id' => $festival->id,
            'panitia_id'  => $panitia->id,
            'divisi_id'   => $divisi->id,
            'jabatan'     => 'koordinator',
            'status'      => 'aktif',
        ]);

        Sanctum::actingAs($admin);

        // List committee per festival
        $listRes = $this->getJson("/api/v1/festivals/{$festival->id}/committee?divisi_id={$divisi->id}");
        $listRes->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.panitia.email', 'nadia@futurise.id');

        // Create and list access roles
        $roleRes = $this->postJson('/api/v1/access-roles', [
            'kode'      => 'kurator_karya',
            'nama'      => 'Kurator Karya Lomba',
            'deskripsi' => 'Berwenang meninjau dan meloloskan karya peserta.',
            'izin'      => ['works.view', 'works.review'],
        ]);
        $roleRes->assertCreated()
            ->assertJsonPath('data.kode', 'kurator_karya');

        $roleId = $roleRes->json('data.id');

        $this->getJson('/api/v1/access-roles')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        // Assign role to committee (twice to verify idempotency)
        $assignRes = $this->postJson("/api/v1/committee/{$panitia->id}/roles", [
            'peran_id'    => $roleId,
            'festival_id' => $festival->id,
        ]);
        $assignRes->assertOk()
            ->assertJsonPath('data.peran_akses.0.kode', 'kurator_karya');

        $this->postJson("/api/v1/committee/{$panitia->id}/roles", [
            'peran_id'    => $roleId,
            'festival_id' => $festival->id,
        ])->assertOk();

        $this->assertDatabaseHas('panitia_peran', [
            'panitia_id'  => $panitia->id,
            'peran_id'    => $roleId,
            'festival_id' => $festival->id,
        ]);
        $this->assertEquals(1, PeranAkses::count());
    }

    public function test_institution_seeder_and_search_endpoint_work_for_aliases_and_smk(): void
    {
        $this->seed(ReferensiInstitusiSeeder::class);
        $initialCount = \App\Models\ReferensiInstitusi::count();
        $this->assertGreaterThan(0, $initialCount);

        // Idempotent when already populated
        $this->seed(ReferensiInstitusiSeeder::class);
        $this->assertEquals($initialCount, \App\Models\ReferensiInstitusi::count());

        $ptRes = $this->getJson('/api/v1/institusi/search?q=itenas&kategori=perguruan_tinggi');
        $ptRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.nama', 'Institut Teknologi Nasional Bandung');

        $smkRes = $this->getJson('/api/v1/institusi/search?q=smk%201%20bandung&kategori=smk');
        $smkRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.nama', 'SMKN 1 BANDUNG');

        // Prefix & phrase ranking: "institut teknologi" puts "Institut Teknologi ..." above "Institut Bisnis dan Teknologi..."
        \App\Models\ReferensiInstitusi::create([
            'nama'     => 'Institut Bisnis dan Teknologi Indonesia',
            'kategori' => 'perguruan_tinggi',
            'provinsi' => 'Prov. Bali',
            'kab_kota' => 'Kota Denpasar',
        ]);
        \App\Models\ReferensiInstitusi::create([
            'nama'     => 'Institut Teknologi Sepuluh Nopember',
            'kategori' => 'perguruan_tinggi',
            'provinsi' => 'Prov. Jawa Timur',
            'kab_kota' => 'Kota Surabaya',
        ]);

        $rankRes = $this->getJson('/api/v1/institusi/search?q=institut%20teknologi&kategori=perguruan_tinggi');
        $rankRes->assertOk();
        $names = collect($rankRes->json('data'))->pluck('nama')->all();
        // West Java "Institut Teknologi Bandung" and "Institut Teknologi Nasional Bandung" must outrank "Institut Bisnis dan Teknologi..."
        $this->assertEquals('Institut Teknologi Bandung', $names[0]);
        $this->assertLessThan(
            array_search('Institut Bisnis dan Teknologi Indonesia', $names, true),
            array_search('Institut Teknologi Sepuluh Nopember', $names, true)
        );

        // Abbreviation expansion & English alias + lightweight typo fallback
        $this->getJson('/api/v1/institusi/search?q=univ%20padjadjaran&kategori=perguruan_tinggi')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Universitas Padjadjaran');

        $this->getJson('/api/v1/institusi/search?q=telkom%20university&kategori=perguruan_tinggi')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Universitas Telkom');

        $this->getJson('/api/v1/institusi/search?q=institu%20teknologi&kategori=perguruan_tinggi')
            ->assertOk()
            ->assertJsonPath('data.0.nama', 'Institut Teknologi Bandung');

        $preloadRes = $this->getJson('/api/v1/institusi/search?preload=1&kategori=perguruan_tinggi');
        $preloadRes->assertOk()
            ->assertJsonPath('success', true);
        $this->assertIsArray($preloadRes->json('data.0'));
        $this->assertNotEmpty($preloadRes->json('data.0.0'));
    }
}
