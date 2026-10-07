<section class="relative overflow-hidden pt-4 pb-10 sm:pb-12">
    <!-- Grid Background -->
    <svg aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full">
        <defs>
            <pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse">
                <path d="M32 0H0V32" fill="none" stroke="#E3DFD5" stroke-width="1"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#grid)"/>
        <g class="hidden md:block">
            <rect x="calc(100% - 320px)" y="96" width="32" height="32" fill="#E6ECF4"/>
            <rect x="calc(100% - 288px)" y="96" width="32" height="32" fill="#E6ECF4"/>
            <rect x="calc(100% - 288px)" y="128" width="32" height="32" fill="#1F3A5F" opacity="0.9"/>
            <rect x="calc(100% - 896px)" y="192" width="32" height="32" fill="#E6ECF4"/>
        </g>
    </svg>

    <!-- Floating Guest Navbar -->
    <div class="relative z-10 max-w-[1100px] mx-auto px-5 sm:px-8 mb-[40px]">
        <header class="bg-surface border border-[#D9D5C9] rounded-[10px] px-4 py-2.5 flex items-center justify-between text-sm shadow-xs">
            <a href="/" class="flex items-center space-x-2.5 text-accent font-medium text-sm shrink-0" aria-label="PhilNITS Prep Home">
                <img src="{{ asset('logo-mark.svg') }}" alt="" class="w-[22px] h-[22px] shrink-0" style="width:22px; height:22px;" aria-hidden="true">
                <span class="font-medium text-accent text-sm">PhilNITS Prep</span>
            </a>

            <div class="flex items-center space-x-4 sm:space-x-6 text-xs sm:text-sm shrink-0">
                <a href="#study-loop" class="hidden sm:inline-block text-stone hover:text-ink transition-colors">Study loop</a>
                <a href="#faq" class="hidden sm:inline-block text-stone hover:text-ink transition-colors">FAQ</a>
                <a href="{{ route('login') }}" class="inline-block text-stone hover:text-ink transition-colors">Log in</a>
                <a href="{{ route('register') }}" class="inline-block bg-accent text-surface hover:bg-accent/90 rounded-[6px] px-3.5 py-1.5 text-xs font-medium transition-colors shrink-0">
                    Register
                </a>
            </div>
        </header>
    </div>

    <!-- Split Headline Row -->
    <div class="relative z-10 max-w-[1100px] mx-auto px-5 sm:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 md:gap-10">
            <!-- Left Column -->
            <div class="w-full md:flex-1 md:max-w-[480px] text-left">
                <span class="font-mono text-xs text-stone tracking-normal block mb-2">ITPEC IT Passport / reviewer</span>
                <h1 class="font-medium text-[34px] sm:text-[44px] text-ink leading-[1.04] tracking-[-0.035em]">
                    Practice the IT Passport exam, one question at a time.
                </h1>
            </div>

            <!-- Right Column -->
            <div class="w-full md:w-[240px] shrink-0 text-left">
                <p class="text-sm text-stone leading-relaxed mb-4">
                    Topic practice with explanations, a list of every question you got wrong, and timed assessments.
                </p>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('register') }}" class="inline-block bg-accent text-surface hover:bg-accent/90 rounded-[8px] px-4 py-2 text-sm font-medium transition-colors">
                        Get started
                    </a>
                    <a href="#study-loop" class="inline-block text-accent text-sm font-medium underline decoration-accent decoration-1 underline-offset-4 hover:opacity-80 transition-opacity">
                        How it works
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Wide App Preview Container -->
    <x-welcome.app-preview />
</section>
