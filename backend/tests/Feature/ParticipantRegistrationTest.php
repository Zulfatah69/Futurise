<?php

namespace Tests\Feature;

use App\Models\Peserta;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Tahap 1: User mendaftar akun baru (hanya data akun, tanpa data peserta).
     */
    public function test_user_can_register_account_via_api_without_peserta_data(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'phone'                 => '081234567890',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.account_status', 'active');

        $this->assertDatabaseHas('users', [
            'email'          => 'budi@example.com',
            'account_status' => 'active',
        ]);

        // Belum ada data peserta saat tahap 1
        $this->assertDatabaseMissing('peserta', [
            'email' => 'budi@example.com',
        ]);
    }

    /**
     * Tahap 2: User yang login mendaftarkan diri sebagai peserta lomba via API.
     */
    public function test_authenticated_user_can_register_as_competition_participant(): void
    {
        // Register akun dulu
        $register = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $token = $register->json('data.token');

        // Daftarkan profil peserta (Tahap 2)
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/participants/register', [
                'institusi'       => 'Universitas Telkom',
                'jenis_peserta'   => 'mahasiswa',
                'nomor_identitas' => '1201210001',
                'no_telepon'      => '081234567890',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.peserta.institusi', 'Universitas Telkom');

        $this->assertDatabaseHas('peserta', [
            'email'           => 'budi@example.com',
            'institusi'       => 'Universitas Telkom',
            'nomor_identitas' => '1201210001',
            'jenis_peserta'   => 'mahasiswa',
        ]);
    }

    /**
     * User dapat mendaftar profil peserta lomba via Web Form di Dashboard.
     */
    public function test_user_can_register_participant_via_web_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'account_status'    => 'active',
        ]);

        $response = $this->actingAs($user)->post('/peserta/daftar', [
            'institusi'       => 'Institut Teknologi Nasional (ITENAS)',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '152024183',
            'no_telepon'      => '085189920124',
        ]);

        $response->assertRedirect('/dashboard')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('peserta', [
            'user_id'         => $user->id,
            'institusi'       => 'Institut Teknologi Nasional (ITENAS)',
            'nomor_identitas' => '152024183',
            'jenis_peserta'   => 'mahasiswa',
        ]);
    }

    /**
     * User yang login dapat mengambil profil dan melihat data pesertanya.
     */
    public function test_user_can_login_and_access_profile_via_api(): void
    {
        $user = User::factory()->create([
            'email'             => 'siti@unpad.ac.id',
            'password'          => bcrypt('password123'),
            'account_status'    => 'active',
            'email_verified_at' => now(),
        ]);

        Peserta::create([
            'user_id'         => $user->id,
            'nama_lengkap'    => $user->name,
            'email'           => $user->email,
            'institusi'       => 'Universitas Padjadjaran',
            'jenis_peserta'   => 'mahasiswa',
            'nomor_identitas' => '140810200001',
        ]);

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email'    => 'siti@unpad.ac.id',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        $token = $loginResponse->json('data.token');

        $meResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');

        $meResponse->assertStatus(200)
            ->assertJsonPath('data.peserta.institusi', 'Universitas Padjadjaran');
    }
}
