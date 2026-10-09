<x-guest-layout>
    <div class="text-left space-y-1">
        <h2 class="text-2xl font-bold text-ink tracking-tight">
            Verify Email Address
        </h2>
        <p class="text-xs sm:text-sm text-stone">
            Please enter the 6-digit code sent to <strong class="text-ink font-semibold">{{ $user->email }}</strong>
        </p>
    </div>

    <!-- Status & Error Alerts -->
    @if (session('status'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->has('code'))
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $errors->first('code') }}</span>
        </div>
    @endif

    <!-- OTP Form -->
    <form method="POST" action="{{ route('verification.otp.verify') }}" id="otp-form" class="space-y-6 pt-2">
        @csrf

        <input type="hidden" name="code" id="full-code-input" value="">

        <!-- 6-Digit Boxes -->
        <div class="flex items-center justify-between gap-2 sm:gap-3" id="otp-boxes-container">
            @for ($i = 0; $i < 6; $i++)
                <input type="text"
                       maxlength="1"
                       pattern="[0-9]*"
                       inputmode="numeric"
                       data-index="{{ $i }}"
                       class="otp-digit-input w-11 h-13 sm:w-12 sm:h-14 text-center text-xl font-bold text-ink bg-surface border-2 border-[#D9D5C9] rounded-xl focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all outline-none"
                       autocomplete="off"
                       aria-label="Digit {{ $i + 1 }}">
            @endfor
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" id="verify-submit-btn" class="w-full inline-flex items-center justify-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-5 py-3 text-sm font-semibold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2">
                <span>Verify Email & Continue</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Resend & Logout Options -->
    <div class="space-y-3 pt-4 border-t border-[#E3DFD5]">
        <div class="flex items-center justify-between text-xs sm:text-sm">
            <span class="text-stone">Didn't get a code?</span>
            <form method="POST" action="{{ route('verification.otp.resend') }}" id="resend-form">
                @csrf
                <button type="submit" id="resend-btn" class="font-bold text-accent hover:underline disabled:opacity-50 disabled:cursor-not-allowed">
                    Resend Code
                </button>
            </form>
        </div>

        <div class="text-center pt-2">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-stone hover:text-ink underline">
                    Log out or use a different email address
                </button>
            </form>
        </div>
    </div>

    <!-- OTP Input JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-digit-input');
            const fullInput = document.getElementById('full-code-input');
            const form = document.getElementById('otp-form');
            const resendBtn = document.getElementById('resend-btn');

            // Auto-focus first input
            if (inputs.length > 0) {
                inputs[0].focus();
            }

            function updateFullCode() {
                let code = '';
                inputs.forEach(input => code += input.value.trim());
                fullInput.value = code;
            }

            inputs.forEach((input, idx) => {
                input.addEventListener('input', function (e) {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val;

                    if (val && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }

                    updateFullCode();

                    // If all 6 digits entered, auto-submit
                    if (Array.from(inputs).every(i => i.value.trim().length === 1)) {
                        form.submit();
                    }
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                });

                input.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                    if (digits.length > 0) {
                        digits.split('').forEach((d, i) => {
                            if (inputs[i]) {
                                inputs[i].value = d;
                            }
                        });

                        const nextIndex = Math.min(digits.length, inputs.length - 1);
                        inputs[nextIndex].focus();
                        updateFullCode();

                        if (digits.length === 6) {
                            form.submit();
                        }
                    }
                });
            });

            // Handle resend countdown if disabled or on click
            let cooldown = 0;
            @if (session('error') && str_contains(session('error'), 'Please wait'))
                // Parse remaining seconds if available
                const match = "{{ session('error') }}".match(/wait (\d+) seconds/);
                if (match && match[1]) {
                    cooldown = parseInt(match[1]);
                }
            @endif

            if (cooldown > 0) {
                startCooldown(cooldown);
            }

            function startCooldown(seconds) {
                resendBtn.disabled = true;
                let current = seconds;

                const timer = setInterval(() => {
                    resendBtn.textContent = `Resend Code (${current}s)`;
                    current--;
                    if (current < 0) {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Resend Code';
                    }
                }, 1000);
            }
        });
    </script>
</x-guest-layout>
