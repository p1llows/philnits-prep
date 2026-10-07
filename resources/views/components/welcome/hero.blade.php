<section x-data="pwaInstall" class="relative overflow-hidden pt-6 sm:pt-8 pb-12 sm:pb-16">
    <!-- Graph Paper Grid Background -->
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
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 sm:mb-14">
        <header class="bg-surface/90 backdrop-blur-sm border border-[#D9D5C9] rounded-xl px-5 py-3 flex items-center justify-between text-sm shadow-xs hover:border-accent/30 transition-all duration-300">
            <a href="/" class="flex items-center space-x-2.5 text-accent font-medium text-sm shrink-0 group" aria-label="PhilNITS Prep Home">
                <img src="{{ asset('logo-mark.svg') }}" alt="" class="w-6 h-6 shrink-0 group-hover:scale-110 transition-transform duration-200" aria-hidden="true">
                <span class="font-medium text-accent text-base tracking-tight group-hover:text-ink transition-colors duration-200">PhilNITS Prep</span>
            </a>

            <div class="flex items-center space-x-5 sm:space-x-8 text-xs sm:text-sm shrink-0">
                <a href="#study-loop" class="hidden sm:inline-block text-stone hover:text-accent transition-colors duration-200">Study loop</a>
                <a href="#faq" class="hidden sm:inline-block text-stone hover:text-accent transition-colors duration-200">FAQ</a>
                <button @click="installApp()" type="button" class="hidden sm:flex items-center space-x-1.5 text-accent hover:text-ink hover:-translate-y-0.5 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>App</span>
                </button>
                <a href="{{ route('login') }}" class="inline-block text-stone hover:text-ink transition-colors duration-200">Log in</a>
                <a href="{{ route('register') }}" class="inline-block bg-accent text-surface hover:bg-accent/90 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 rounded-lg px-4 py-2 text-xs sm:text-sm font-medium transition-all duration-200 shrink-0">
                    Register
                </a>
            </div>
        </header>
    </div>

    <!-- Split Headline Row -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 md:gap-12">
            <!-- Left Column -->
            <div class="w-full md:flex-1 md:max-w-2xl text-left">
                <span class="font-mono text-xs sm:text-sm text-stone tracking-normal block mb-3">ITPEC IT Passport / reviewer</span>
                <h1 class="font-medium text-3xl sm:text-5xl lg:text-[56px] text-ink leading-[1.06] tracking-[-0.03em]">
                    Practice the IT Passport exam, one question at a time.
                </h1>
            </div>

            <!-- Right Column -->
            <div class="w-full md:w-[320px] lg:w-[360px] shrink-0 text-left">
                <p class="text-sm sm:text-base text-stone leading-relaxed mb-6">
                    Topic practice with explanations, a list of every question you got wrong, and timed assessments.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="inline-block bg-accent text-surface hover:bg-accent/90 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 rounded-lg px-5 py-2.5 text-sm font-medium transition-all duration-200 shadow-xs">
                        Get started
                    </a>
                    <button @click="installApp()" type="button" class="inline-flex items-center space-x-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper hover:border-accent/40 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200 shadow-xs">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download app</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Wide App Preview Container -->
    <x-welcome.app-preview />
</section>
