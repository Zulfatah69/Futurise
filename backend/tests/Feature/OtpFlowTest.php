<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use App\Services\OtpService;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_otp_verification_page_renders_six_digit_inputs_and_formatted_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name'              => 'Nadia Putri',
            'email'             => 'nadia.otp@futurise.test',
            'email_verified_at' => null,
            'account_status'    => 'pending_approval',
        ]);
        $user->assignRole('pengunjung');

        app(OtpService::class)->send($user);

        $this->actingAs($user)
            ->get('/verify-otp')
            ->assertOk()
            ->assertSee('Verifikasi Email Anda')
            ->assertSee('otp-digit-0')
            ->assertSee('otp-digit-5')
            ->assertSee('name="otp"', false)
            ->assertSee('nadia.otp@futurise.test');

        // Resend segera selama cooldown wajib menampilkan detik bulat tanpa angka desimal
        $this->actingAs($user)
            ->post('/verify-otp/resend')
            ->assertSessionHasErrors('otp');

        try {
            app(OtpService::class)->send($user);
            $this->fail('Seharusnya melempar RuntimeException saat masa cooldown.');
        } catch (\RuntimeException $exception) {
            $this->assertMatchesRegularExpression('/^Tunggu \d+ detik sebelum meminta OTP baru\.$/', $exception->getMessage());
        }
    }

    public function test_otp_email_renders_clean_table_layout_and_copyable_code_without_emoji(): void
    {
        $html = (new OtpMail('482910', 'Budi Santoso', 10))->render();

        $this->assertStringContainsString('482910', $html);
        $this->assertStringContainsString('Halo, Budi Santoso', $html);
        $this->assertStringContainsString('Berlaku 10 menit', $html);
        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertStringNotContainsString('⏱', $html);
    }
}
