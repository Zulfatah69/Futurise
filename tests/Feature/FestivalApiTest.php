<?php

namespace Tests\Feature;

use App\Models\Festival;
use App\Models\User;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FestivalApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);
    }

    /**
     * Frontend dapat mengambil festival aktif beserta periode dan kategori.
     */
    public function test_can_get_active_festival_with_periods_and_categories(): void
    {
        $response = $this->getJson('/api/v1/festivals/active');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'futurise-2026')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'nama',
                    'slug',
                    'edisi',
                    'tahun',
                    'tema',
                    'periodes',
                    'kategoris',
                ],
            ]);

        $this->assertCount(5, $response->json('data.periodes'));
        $this->assertCount(5, $response->json('data.kategoris'));
    }

    /**
     * Publik dapat melihat daftar festival.
     */
    public function test_can_get_festivals_list(): void
    {
        $response = $this->getJson('/api/v1/festivals');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => [
                    'current_page',
                    'total',
                ],
            ]);
    }

    /**
     * Publik dapat melihat detail festival berdasarkan slug.
     */
    public function test_can_get_festival_by_slug(): void
    {
        $response = $this->getJson('/api/v1/festivals/futurise-2026');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'FUTURiSE 2026');
    }

    /**
     * Pengguna tanpa autentikasi ditolak saat mencoba membuat festival.
     */
    public function test_unauthenticated_cannot_create_festival(): void
    {
        $response = $this->postJson('/api/v1/festivals', [
            'nama'            => 'FUTURiSE 2027',
            'edisi'           => 'Edisi 2',
            'tahun'           => 2027,
            'tanggal_mulai'   => '2027-11-01',
            'tanggal_selesai' => '2027-12-15',
        ]);

        $response->assertUnauthorized();
    }

    /**
     * Pengguna terautentikasi dapat membuat festival baru dan menambahkan periode serta kategori.
     */
    public function test_authenticated_user_can_manage_festival(): void
    {
        $admin = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);
        Sanctum::actingAs($admin);

        // 1. Buat Festival Baru
        $createRes = $this->postJson('/api/v1/festivals', [
            'nama'            => 'FUTURiSE 2027',
            'edisi'           => 'Edisi 2',
            'tahun'           => 2027,
            'tanggal_mulai'   => '2027-11-01',
            'tanggal_selesai' => '2027-12-15',
            'aktif'           => false,
        ]);

        $createRes->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'futurise-2027-2027');

        $newId = $createRes->json('data.id');

        // 2. Tambah Periode
        $periodRes = $this->postJson("/api/v1/festivals/{$newId}/periods", [
            'tahap'   => 'pendaftaran',
            'mulai'   => '2027-11-01 08:00:00',
            'selesai' => '2027-11-20 23:59:59',
        ]);

        $periodRes->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.tahap', 'pendaftaran');

        // 3. Tambah Kategori
        $categoryRes = $this->postJson("/api/v1/festivals/{$newId}/categories", [
            'nama'   => 'Smart Campus Automation',
            'kuota'  => 50,
            'urutan' => 1,
        ]);

        $categoryRes->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nama', 'Smart Campus Automation');
    }
}
