<?php

namespace Tests\Unit;

use App\Models\EmailOtp;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OtpService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OtpService();
    }

    public function test_can_generate_six_digit_otp(): void
    {
        $result = $this->service->generate('user@example.com');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(6, strlen($result['code']));
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $result['code']);

        $this->assertDatabaseHas('email_otps', [
            'email' => 'user@example.com',
            'purpose' => 'email_verification',
        ]);
    }

    public function test_enforces_60_second_resend_cooldown(): void
    {
        $first = $this->service->generate('user@example.com');
        $this->assertTrue($first['success']);

        $second = $this->service->generate('user@example.com');
        $this->assertFalse($second['success']);
        $this->assertStringContainsString('Please wait', $second['message']);
    }

    public function test_can_verify_correct_otp(): void
    {
        $generated = $this->service->generate('user@example.com');
        $code = $generated['code'];

        $result = $this->service->verify('user@example.com', $code);

        $this->assertTrue($result['success']);
        $this->assertDatabaseMissing('email_otps', ['email' => 'user@example.com']);
    }

    public function test_rejects_incorrect_otp_and_increments_attempts(): void
    {
        $this->service->generate('user@example.com');

        $result = $this->service->verify('user@example.com', '000000');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid OTP code', $result['message']);

        $otp = EmailOtp::where('email', 'user@example.com')->first();
        $this->assertNotNull($otp);
        $this->assertEquals(1, $otp->attempts);
    }

    public function test_invalidates_otp_after_max_failed_attempts(): void
    {
        $this->service->generate('user@example.com');

        for ($i = 0; $i < 5; $i++) {
            $result = $this->service->verify('user@example.com', '000000');
        }

        $this->assertFalse($result['success']);
        $this->assertDatabaseMissing('email_otps', ['email' => 'user@example.com']);
    }

    public function test_rejects_expired_otp(): void
    {
        $this->service->generate('user@example.com');

        // Travel 11 minutes into future
        Carbon::setTestNow(now()->addMinutes(11));

        $result = $this->service->verify('user@example.com', '123456');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('expired', $result['message']);
    }
}
