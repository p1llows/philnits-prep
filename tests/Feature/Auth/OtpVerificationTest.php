<?php

namespace Tests\Feature\Auth;

use App\Mail\SendOtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_otp_and_redirects_to_verify_screen(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Learner',
            'email' => 'learner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/verify-otp');
        $this->assertAuthenticated();

        $user = User::where('email', 'learner@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_user_can_verify_email_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otpService = app(OtpService::class);
        $generated = $otpService->generate($user->email);

        $response = $this->actingAs($user)->post('/verify-otp', [
            'code' => $generated['code'],
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('status');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_user_cannot_verify_email_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otpService = app(OtpService::class);
        $otpService->generate($user->email);

        $response = $this->actingAs($user)->post('/verify-otp', [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_user_can_request_otp_resend(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/verify-otp/resend');

        $response->assertSessionHas('status');
        Mail::assertSent(SendOtpMail::class);
    }

    public function test_unverified_user_is_redirected_from_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/verify-otp');
    }
}
