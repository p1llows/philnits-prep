<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use App\Mail\SendOtpMail;
use App\Services\OtpService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        if (!app(\App\Services\AppSettings::class)->get('open_registration', true)) {
            return redirect()->route('login')
                ->with('status', 'Registration is currently closed by the administrator.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, OtpService $otpService)
    {
        if (!app(\App\Services\AppSettings::class)->get('open_registration', true)) {
            abort(403, 'Registration is currently closed.');
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Generate OTP and send email
        $otpResult = $otpService->generate($user->email);
        if ($otpResult['success']) {
            try {
                Mail::to($user->email)->send(new SendOtpMail($otpResult['code'], $user->name));
            } catch (\Throwable $e) {
                Log::warning("Failed to dispatch OTP email to {$user->email}: " . $e->getMessage());
                Log::info("DEV FALLBACK OTP for {$user->email}: [ {$otpResult['code']} ]");
            }
        }

        return redirect()->route('verification.notice');
    }
}
