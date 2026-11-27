<?php

namespace Tests\Feature;

use App\Filament\Resources\DivisiPanitias\DivisiPanitiaResource;
use App\Filament\Resources\Festivals\FestivalResource;
use App\Filament\Resources\Karyas\KaryaResource;
use App\Filament\Resources\Panitias\PanitiaResource;
use App\Filament\Resources\PublikasiKaryas\PublikasiKaryaResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\Karya;
use App\Models\Panitia;
use App\Models\Peserta;
use App\Models\PublikasiKarya;
use App\Models\User;
use Database\Seeders\DemoAccountSeeder;
use Database\Seeders\FestivalSeeder;
use Database\Seeders\RoleSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(FestivalSeeder::class);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    private function createUserWithRole(string $role, string $status = 'active'): User
    {
        $user = User::factory()->create([
            'account_status'    => $status,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    public function test_super_admin_has_full_access_and_cannot_delete_self(): void
    {
        $superAdmin = $this->createUserWithRole('super-admin');
        $otherUser = $this->createUserWithRole('tim-mahasiswa');
        $festival = Festival::firstOrFail();

        $this->actingAs($superAdmin);

        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertTrue(KaryaResource::canViewAny());
        $this->assertTrue(PublikasiKaryaResource::canViewAny());
        $this->assertTrue(PanitiaResource::canViewAny());
        $this->assertTrue(DivisiPanitiaResource::canViewAny());

        $this->assertTrue(UserResource::canDelete($otherUser));
        $this->assertFalse(UserResource::canDelete($superAdmin));

        $this->get(UserResource::getUrl('index'))->assertOk();
        $this->get(FestivalResource::getUrl('index'))->assertOk();
        $this->get(KaryaResource::getUrl('index'))->assertOk();
        $this->get(PublikasiKaryaResource::getUrl('index'))->assertOk();
        $this->get(PanitiaResource::getUrl('index'))->assertOk();
        $this->get(DivisiPanitiaResource::getUrl('index'))->assertOk();
        $this->assertTrue(FestivalResource::canEdit($festival));
    }

    public function test_program_kurasi_only_sees_karya_festival_and_readonly_publikasi(): void
    {
        $kurator = $this->createUserWithRole('program-kurasi');
        $pesertaOwner = $this->createUserWithRole('tim-mahasiswa');
        $peserta = Peserta::create([
            'user_id'       => $pesertaOwner->id,
            'nama_lengkap'  => $pesertaOwner->name,
            'email'         => $pesertaOwner->email,
            'institusi'     => 'ITB',
            'jenis_peserta' => 'mahasiswa',
        ]);
        $festival = Festival::firstOrFail();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Sistem Sensor Pintar',
            'deskripsi'   => 'Deskripsi karya.',
            'kategori'    => 'IoT',
            'teknologi'   => 'ESP32',
            'status'      => 'diajukan',
        ]);
        $publikasi = PublikasiKarya::create([
            'karya_id'     => $karya->id,
            'kanal'        => 'website',
            'slug'         => 'sistem-sensor-pintar',
            'judul_publik' => 'Sistem Sensor Pintar',
            'status'       => 'draft',
        ]);

        $this->actingAs($kurator);

        $this->assertTrue(KaryaResource::canViewAny());
        $this->assertTrue(KaryaResource::canEdit($karya));
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertTrue(PublikasiKaryaResource::canViewAny());
        $this->assertFalse(PublikasiKaryaResource::canEdit($publikasi));

        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(PanitiaResource::canViewAny());
        $this->assertFalse(DivisiPanitiaResource::canViewAny());

        $this->get(KaryaResource::getUrl('index'))->assertOk();
        $this->get(FestivalResource::getUrl('index'))->assertOk();
        $this->get(PublikasiKaryaResource::getUrl('index'))->assertOk();
        $this->get(UserResource::getUrl('index'))->assertForbidden();
        $this->get(PanitiaResource::getUrl('index'))->assertForbidden();
        $this->get(DivisiPanitiaResource::getUrl('index'))->assertForbidden();
    }

    public function test_publikasi_only_manages_showcase_and_reads_karya_and_festival(): void
    {
        $pubUser = $this->createUserWithRole('publikasi');
        $pesertaOwner = $this->createUserWithRole('tim-mahasiswa');
        $peserta = Peserta::create([
            'user_id'       => $pesertaOwner->id,
            'nama_lengkap'  => $pesertaOwner->name,
            'email'         => $pesertaOwner->email,
            'institusi'     => 'ITENAS',
            'jenis_peserta' => 'mahasiswa',
        ]);
        $festival = Festival::firstOrFail();
        $karya = Karya::create([
            'festival_id' => $festival->id,
            'peserta_id'  => $peserta->id,
            'judul'       => 'Aplikasi Smart Campus',
            'deskripsi'   => 'Deskripsi karya.',
            'kategori'    => 'Web',
            'teknologi'   => 'Laravel',
            'status'      => 'lolos',
        ]);
        $publikasi = PublikasiKarya::create([
            'karya_id'     => $karya->id,
            'kanal'        => 'website',
            'slug'         => 'aplikasi-smart-campus',
            'judul_publik' => 'Aplikasi Smart Campus',
            'status'       => 'menunggu_persetujuan',
        ]);

        $this->actingAs($pubUser);

        $this->assertTrue(PublikasiKaryaResource::canViewAny());
        $this->assertTrue(PublikasiKaryaResource::canCreate());
        $this->assertTrue(PublikasiKaryaResource::canEdit($publikasi));
        $this->assertTrue(PublikasiKaryaResource::canDelete($publikasi));

        $this->assertTrue(KaryaResource::canViewAny());
        $this->assertFalse(KaryaResource::canEdit($karya));
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertFalse(FestivalResource::canEdit($festival));

        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(PanitiaResource::canViewAny());
        $this->assertFalse(DivisiPanitiaResource::canViewAny());

        $this->get(PublikasiKaryaResource::getUrl('index'))->assertOk();
        $this->get(KaryaResource::getUrl('index'))->assertOk();
        $this->get(UserResource::getUrl('index'))->assertForbidden();
        $this->get(PanitiaResource::getUrl('index'))->assertForbidden();
    }

    public function test_sekretariat_manages_users_panitia_and_divisi_without_modifying_super_admin(): void
    {
        $sekretariat = $this->createUserWithRole('sekretariat');
        $superAdmin = $this->createUserWithRole('super-admin');
        $pesertaUser = $this->createUserWithRole('tim-mahasiswa');
        $festival = Festival::firstOrFail();
        $panitia = Panitia::create([
            'nama_lengkap' => 'Siti Panitia',
            'email'        => 'siti.panitia@futurise.id',
            'jenis'        => 'internal',
            'aktif'        => true,
        ]);
        $divisi = DivisiPanitia::create([
            'festival_id' => $festival->id,
            'nama'        => 'Kesekretariatan',
        ]);

        $this->actingAs($sekretariat);

        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(UserResource::canCreate());
        $this->assertTrue(UserResource::canEdit($pesertaUser));
        $this->assertFalse(UserResource::canEdit($superAdmin));
        $this->assertFalse(UserResource::canDelete($pesertaUser));

        $this->assertTrue(PanitiaResource::canViewAny());
        $this->assertTrue(PanitiaResource::canEdit($panitia));
        $this->assertTrue(DivisiPanitiaResource::canViewAny());
        $this->assertTrue(DivisiPanitiaResource::canEdit($divisi));

        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertFalse(FestivalResource::canEdit($festival));
        $this->assertFalse(PublikasiKaryaResource::canViewAny());

        $this->get(UserResource::getUrl('index'))->assertOk();
        $this->get(PanitiaResource::getUrl('index'))->assertOk();
        $this->get(DivisiPanitiaResource::getUrl('index'))->assertOk();
        $this->get(PublikasiKaryaResource::getUrl('index'))->assertForbidden();
    }

    public function test_specialized_roles_only_see_their_designated_resources(): void
    {
        $kampus = $this->createUserWithRole('kampus-sekolah');
        $keuangan = $this->createUserWithRole('keuangan');
        $sponsor = $this->createUserWithRole('industri-sponsor');
        $venue = $this->createUserWithRole('venue-teknis');
        $inactiveAdmin = $this->createUserWithRole('program-kurasi', 'inactive');

        $this->actingAs($kampus);
        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(KaryaResource::canViewAny());
        $this->assertFalse(FestivalResource::canViewAny());
        $this->assertFalse(PanitiaResource::canViewAny());

        $this->actingAs($keuangan);
        $this->assertTrue(UserResource::canViewAny());
        $this->assertFalse(UserResource::canCreate());
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertFalse(KaryaResource::canViewAny());

        $this->actingAs($sponsor);
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertTrue(PublikasiKaryaResource::canViewAny());
        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(KaryaResource::canViewAny());

        $this->actingAs($venue);
        $this->assertTrue(FestivalResource::canViewAny());
        $this->assertTrue(KaryaResource::canViewAny());
        $this->assertTrue(PanitiaResource::canViewAny());
        $this->assertFalse(DivisiPanitiaResource::canViewAny());
        $this->assertFalse(UserResource::canViewAny());

        $this->actingAs($inactiveAdmin);
        $this->assertFalse(KaryaResource::canViewAny());
        $this->get(KaryaResource::getUrl('index'))->assertForbidden();
    }

    public function test_demo_account_seeder_creates_verified_accounts_for_every_role(): void
    {
        $this->seed(DemoAccountSeeder::class);

        $this->assertDatabaseHas('users', [
            'email'          => 'kurasi@futurise.test',
            'account_status' => 'active',
        ]);
        $this->assertDatabaseHas('users', [
            'email'          => 'publikasi@futurise.test',
            'account_status' => 'active',
        ]);
        $this->assertDatabaseHas('peserta', [
            'email'           => 'mahasiswa@futurise.test',
            'nomor_identitas' => '152026999',
        ]);
        $this->assertDatabaseHas('divisi_panitia', [
            'nama' => 'Program & Kurasi Karya',
        ]);
        $this->assertDatabaseHas('panitia', [
            'email' => 'kurasi@futurise.test',
        ]);
        $this->assertDatabaseHas('peran_akses', [
            'kode' => 'kurator_karya',
        ]);
    }

    public function test_non_admin_roles_render_distinct_dashboard_views(): void
    {
        $this->seed(DemoAccountSeeder::class);

        $juri = User::where('email', 'juri@futurise.test')->firstOrFail();
        $this->actingAs($juri)->get('/dashboard')
            ->assertOk()
            ->assertSee('Portal Dewan Juri')
            ->assertSee('JURI')
            ->assertDontSee('Lengkapi Data Peserta');

        $picKampus = User::where('email', 'pic.kampus@futurise.test')->firstOrFail();
        $this->actingAs($picKampus)->get('/dashboard')
            ->assertOk()
            ->assertSee('Portal Koordinator Institusi')
            ->assertSee('Ringkasan PIC Kampus')
            ->assertDontSee('Lengkapi Data Peserta');

        $picSponsor = User::where('email', 'pic.sponsor@futurise.test')->firstOrFail();
        $this->actingAs($picSponsor)->get('/dashboard')
            ->assertOk()
            ->assertSee('Portal Kemitraan')
            ->assertSee('PIC Sponsor')
            ->assertDontSee('Lengkapi Data Peserta');

        $pengunjung = User::where('email', 'pengunjung@futurise.test')->firstOrFail();
        $this->actingAs($pengunjung)->get('/dashboard')
            ->assertOk()
            ->assertSee('Portal Pengunjung')
            ->assertSee('Lengkapi Data Peserta');

        $mahasiswa = User::where('email', 'mahasiswa@futurise.test')->firstOrFail();
        $this->actingAs($mahasiswa)->get('/dashboard')
            ->assertOk()
            ->assertSee('Karya yang Didaftarkan')
            ->assertSee('Pendaftaran Karya Inovasi Baru');
    }
}
