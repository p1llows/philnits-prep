<?php

namespace App\Services;

use App\Models\EmailOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class OtpService
{
    /**
     * Resend cooldown duration in seconds.
     */
    public const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * OTP validity duration in minutes.
     */
    public const EXPIRATION_MINUTES = 10;

    /**
     * Maximum allowed verification attempts.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Generate a new 6-digit OTP for an email address.
     *
     * @param string $email
     * @param string $purpose
     * @return array{success: bool, code?: string, message?: string, cooldown_remaining?: int}
     */
    public function generate(string $email, string $purpose = 'email_verification'): array
    {
        $existing = EmailOtp::where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if ($existing) {
            $nextAllowedAt = $existing->created_at->copy()->addSeconds(self::RESEND_COOLDOWN_SECONDS);
            if (Carbon::now()->lt($nextAllowedAt)) {
                $cooldownRemaining = (int) max(1, ceil(Carbon::now()->diffInSeconds($nextAllowedAt, false)));
                if ($cooldownRemaining <= self::RESEND_COOLDOWN_SECONDS) {
                    return [
                        'success' => false,
                        'message' => "Please wait {$cooldownRemaining} seconds before requesting a new code.",
                        'cooldown_remaining' => $cooldownRemaining,
                    ];
                }
            }
        }

        // Delete any existing codes for this email/purpose
        EmailOtp::where('email', $email)->where('purpose', $purpose)->delete();

        // Generate 6-digit numeric OTP
        $rawCode = sprintf('%06d', random_int(0, 999999));

        EmailOtp::create([
            'email' => $email,
            'code' => Hash::make($rawCode),
            'purpose' => $purpose,
            'attempts' => 0,
            'expires_at' => Carbon::now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);

        return [
            'success' => true,
            'code' => $rawCode,
            'email' => $email,
        ];
    }

    /**
     * Verify an input OTP code against the stored hash.
     *
     * @param string $email
     * @param string $inputCode
     * @param string $purpose
     * @return array{success: bool, message: string}
     */
    public function verify(string $email, string $inputCode, string $purpose = 'email_verification'): array
    {
        $otp = EmailOtp::where('email', $email)
            ->where('purpose', $purpose)
            ->first();

        if (!$otp) {
            return [
                'success' => false,
                'message' => 'No OTP found or code has expired. Please request a new code.',
            ];
        }

        if ($otp->isExpired()) {
            $otp->delete();
            return [
                'success' => false,
                'message' => 'This OTP code has expired. Please request a new code.',
            ];
        }

        if ($otp->hasMaxAttemptsReached(self::MAX_ATTEMPTS)) {
            $otp->delete();
            return [
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new code.',
            ];
        }

        if (!Hash::check($inputCode, $otp->code)) {
            $otp->increment('attempts');
            $remaining = self::MAX_ATTEMPTS - $otp->attempts;

            if ($remaining <= 0) {
                $otp->delete();
                return [
                    'success' => false,
                    'message' => 'Too many failed attempts. This OTP is no longer valid. Please request a new code.',
                ];
            }

            return [
                'success' => false,
                'message' => "Invalid OTP code. You have {$remaining} attempt(s) remaining.",
            ];
        }

        // Successfully verified
        $otp->delete();

        return [
            'success' => true,
            'message' => 'Email verified successfully.',
        ];
    }

    /**
     * Clear all OTP codes for an email address.
     */
    public function clear(string $email, string $purpose = 'email_verification'): void
    {
        EmailOtp::where('email', $email)->where('purpose', $purpose)->delete();
    }
}
