<section x-data="pwaInstall" class="relative overflow-hidden pt-24 sm:pt-28 pb-16 lg:pb-24">
    <!-- Graph Paper Grid Background -->
    <svg aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full opacity-60">
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

    <!-- Fixed 9Router Top Navbar -->
    <nav class="fixed top-0 z-50 w-full bg-paper/85 backdrop-blur-md border-b border-[#E3DFD5]">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3 text-accent font-medium text-base shrink-0 group" aria-label="PhilNITS Prep Home">
                <img src="{{ asset('logo-mark.svg') }}" alt="" class="w-7 h-7 shrink-0 transition-transform duration-200 group-hover:scale-105" aria-hidden="true">
                <span class="font-bold text-accent text-lg tracking-tight">PhilNITS Prep</span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="#study-loop" class="text-stone hover:text-ink transition-colors duration-200">Study loop</a>
                <a href="#fields" class="text-stone hover:text-ink transition-colors duration-200">Exam fields</a>
                <a href="#faq" class="text-stone hover:text-ink transition-colors duration-200">FAQ</a>
            </div>

            <div class="flex items-center space-x-3 text-xs sm:text-sm">
                <button @click="installApp()" type="button" class="hidden sm:flex items-center space-x-1.5 border border-[#D9D5C9] bg-surface hover:bg-paper text-accent px-3.5 py-2 rounded-xl font-medium transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>App</span>
                </button>
                <a href="{{ route('login') }}" class="text-stone hover:text-ink transition-colors font-medium px-2 py-2">Log in</a>
                <a href="{{ route('register') }}" class="bg-accent text-surface hover:bg-accent/90 rounded-xl px-4 py-2 text-xs sm:text-sm font-medium transition-all shadow-sm shrink-0">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Split Headline Row (Spacious Wide Container) -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-10 sm:mb-14">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 md:gap-12">
            <!-- Left Column: Big Headline -->
            <div class="w-full md:flex-1 md:max-w-2xl text-left">
                <span class="font-mono text-xs sm:text-sm text-stone tracking-normal block mb-3">ITPEC IT Passport / reviewer</span>
                <h1 class="font-medium text-3xl sm:text-5xl lg:text-[56px] text-ink leading-[1.06] tracking-[-0.03em]">
                    Practice the IT Passport exam, one question at a time.
                </h1>
            </div>

            <!-- Right Column: Subtitle & Action Buttons -->
            <div class="w-full md:w-[320px] lg:w-[360px] shrink-0 text-left">
                <p class="text-sm sm:text-base text-stone leading-relaxed mb-6">
                    Topic practice with explanations, a list of every question you got wrong, and timed assessments.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="inline-block bg-accent text-surface hover:bg-accent/90 rounded-xl px-6 py-3 text-sm sm:text-base font-semibold transition-all shadow-md">
                        Get started
                    </a>
                    <button @click="installApp()" type="button" class="inline-flex items-center space-x-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl px-5 py-3 text-sm sm:text-base font-medium transition-all shadow-xs">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download app</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Wide App Preview Window (Full Max-W-7xl Width Container) -->
    <x-welcome.app-preview />
</section>
