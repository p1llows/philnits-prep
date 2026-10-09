<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Display the OTP verification view.
     */
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        return view('auth.verify-otp', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Handle OTP verification code submission.
     */
    public function verify(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Please enter the 6-digit verification code.',
            'code.digits' => 'The verification code must be exactly 6 digits.',
        ]);

        $user = $request->user();
        $result = $this->otpService->verify($user->email, $request->code);

        if (!$result['success']) {
            return back()->withErrors(['code' => $result['message']])->withInput();
        }

        // Mark user's email as verified
        $user->markEmailAsVerified();

        return redirect()->intended('/dashboard')->with('status', 'Email verified successfully! Welcome to PhilNITS Prep.');
    }

    /**
     * Resend a new OTP verification code to the user's email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $result = $this->otpService->generate($user->email);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        try {
            Mail::to($user->email)->send(new SendOtpMail($result['code'], $user->name));
        } catch (\Throwable $e) {
            // Log fallback in dev mode if mail fails
            Log::warning("Failed to dispatch OTP email to {$user->email}: " . $e->getMessage());
            Log::info("DEV FALLBACK OTP for {$user->email}: [ {$result['code']} ]");
        }

        return back()->with('status', 'A new 6-digit verification code has been sent to ' . $user->email);
    }
}
