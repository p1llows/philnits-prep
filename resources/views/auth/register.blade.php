<x-guest-layout maxWidth="sm:max-w-xl md:max-w-2xl" cardPadding="p-5 sm:p-7" spaceY="space-y-4">
    <!-- Mascot: wave, 88px, centered on card edge -->
    <div class="relative -mt-12 sm:-mt-14 mb-1 flex justify-center pointer-events-none">
        <x-mascot variant="wave" :size="88" class="w-[68px] h-[68px] sm:w-[88px] sm:h-[88px] drop-shadow-md" />
    </div>

    <div class="text-center sm:text-left space-y-0.5">
        <h2 class="text-xl sm:text-2xl font-bold text-ink tracking-tight">
            Create your free account
        </h2>
        <p class="text-xs sm:text-sm text-stone">
            Start practicing the IT Passport exam, tracking mistakes & scores.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3 pt-1">
        @csrf

        <!-- Row 1: First Name & Last Name (Side by Side) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{
            firstName: '{{ old('first_name', explode(' ', old('name', ''))[0] ?? '') }}',
            lastName: '{{ old('last_name', count(explode(' ', old('name', ''))) > 1 ? implode(' ', array_slice(explode(' ', old('name', '')), 1)) : '') }}',
            updateFullName() {
                const full = (this.firstName.trim() + ' ' + this.lastName.trim()).trim();
                const hiddenInput = document.getElementById('name');
                if (hiddenInput) hiddenInput.value = full;
            }
        }">
            <input type="hidden" name="name" id="name" :value="(firstName.trim() + ' ' + lastName.trim()).trim()">

            <div class="space-y-1">
                <x-input-label for="first_name" :value="__('First Name')" class="text-xs font-semibold" />
                <x-text-input id="first_name" x-model="firstName" @input="updateFullName()" class="block w-full px-3 py-2 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" placeholder="Jewel" />
                <x-input-error :messages="$errors->get('first_name') ?: $errors->get('name')" class="mt-0.5 text-xs" />
            </div>

            <div class="space-y-1">
                <x-input-label for="last_name" :value="__('Last Name')" class="text-xs font-semibold" />
                <x-text-input id="last_name" x-model="lastName" @input="updateFullName()" class="block w-full px-3 py-2 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="Ramirez" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-0.5 text-xs" />
            </div>
        </div>

        <!-- Row 2: Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold" />
            <x-text-input id="email" class="block w-full px-3 py-2 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-0.5 text-xs" />
        </div>

        <!-- Row 3: Password & Confirm Password (Side by Side) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="space-y-1">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold" />
                <x-text-input id="password" class="block w-full px-3 py-2 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent"
                                type="password"
                                name="password"
                                required autocomplete="new-password"
                                placeholder="Min. 8 characters" />
                <x-input-error :messages="$errors->get('password')" class="mt-0.5 text-xs" />
            </div>

            <div class="space-y-1">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold" />
                <x-text-input id="password_confirmation" class="block w-full px-3 py-2 rounded-xl border border-[#D9D5C9] bg-surface text-ink text-sm focus:border-accent focus:ring-accent"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Re-enter password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-0.5 text-xs" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2">
                <span>Create Account</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer Login Callout -->
    <div class="pt-3 border-t border-[#E3DFD5] text-center text-xs sm:text-sm text-stone">
        Already have an account?
        <a href="{{ route('login') }}" class="font-bold text-accent hover:underline ms-1">
            Log in here &rarr;
        </a>
    </div>
</x-guest-layout>

