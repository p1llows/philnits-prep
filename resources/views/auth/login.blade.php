<x-guest-layout>
    <div class="text-left space-y-1">
        <h2 class="text-2xl font-bold text-ink tracking-tight">
            Log in to your account
        </h2>
        <p class="text-xs sm:text-sm text-stone">
            Welcome back! Continue your study sessions & mistake drills.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-2">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-accent hover:underline font-medium" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-[#D9D5C9] text-accent shadow-xs focus:ring-accent w-4 h-4 cursor-pointer" name="remember">
                <span class="ms-2.5 text-xs sm:text-sm text-stone font-medium">{{ __('Remember me on this device') }}</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-5 py-3 text-sm font-semibold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2">
                <span>Log In</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer Register Callout -->
    <div class="pt-4 border-t border-[#E3DFD5] text-center text-xs sm:text-sm text-stone">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-bold text-accent hover:underline ms-1">
            Register for free &rarr;
        </a>
    </div>
</x-guest-layout>

