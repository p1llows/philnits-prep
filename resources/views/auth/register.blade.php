<x-guest-layout>
    <div class="text-left space-y-1">
        <h2 class="text-2xl font-bold text-ink tracking-tight">
            Create your free account
        </h2>
        <p class="text-xs sm:text-sm text-stone">
            Start practicing the IT Passport exam, tracking mistakes & scores.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 pt-2">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jewel Ramirez" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="At least 8 characters" />

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Re-enter password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-5 py-3 text-sm font-semibold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2">
                <span>Create Account</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer Login Callout -->
    <div class="pt-4 border-t border-[#E3DFD5] text-center text-xs sm:text-sm text-stone">
        Already have an account?
        <a href="{{ route('login') }}" class="font-bold text-accent hover:underline ms-1">
            Log in here &rarr;
        </a>
    </div>
</x-guest-layout>

