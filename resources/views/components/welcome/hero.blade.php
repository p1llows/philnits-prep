<section x-data="pwaInstall" class="relative overflow-hidden pt-24 sm:pt-28 pb-14 lg:pb-20">
    <!-- Graph Paper Grid Background with subtle radial fade -->
    <div class="absolute inset-0 pointer-events-none">
        <svg aria-hidden="true" class="h-full w-full opacity-60">
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
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-paper to-transparent"></div>
    </div>

    <!-- Fixed Top Navbar -->
    <nav class="fixed top-0 z-50 w-full bg-paper/90 backdrop-blur-md border-b border-[#E3DFD5] transition-all duration-200">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2.5 text-accent font-medium text-base shrink-0 group" aria-label="PhilNITS Prep Home">
                <img src="{{ asset('logo-mark.svg') }}" alt="" class="w-7 h-7 shrink-0 transition-transform duration-200 group-hover:scale-105" aria-hidden="true">
                <span class="font-bold text-accent text-lg tracking-tight">PhilNITS <span class="text-ink font-normal">Prep</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="#study-loop" class="text-stone hover:text-ink transition-colors duration-200">Study Loop</a>
                <a href="#fields" class="text-stone hover:text-ink transition-colors duration-200">Exam Fields</a>
                <a href="#faq" class="text-stone hover:text-ink transition-colors duration-200">FAQ</a>
            </div>

            <div class="flex items-center space-x-3 text-xs sm:text-sm">
                <button @click="installApp()" type="button" class="hidden sm:inline-flex items-center space-x-1.5 border border-[#D9D5C9] bg-surface hover:bg-paper text-accent px-3.5 py-2 rounded-xl font-medium transition-all shadow-xs hover:border-accent/40">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Install App</span>
                </button>
                <a href="{{ route('login') }}" class="text-stone hover:text-ink transition-colors font-medium px-3 py-2">Log in</a>
                <a href="{{ route('register') }}" class="bg-accent text-surface hover:bg-accent/90 rounded-xl px-5 py-2 font-medium transition-all shadow-sm shrink-0 hover:shadow whitespace-nowrap">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Split Headline Row -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-8 sm:mb-12">
        <!-- Eyebrow Pill -->
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-[#D9D5C9] bg-surface/90 text-accent font-mono text-xs mb-6 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
            <span>PhilNITS / ITPEC IT Passport Reviewer</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 md:gap-12">
            <!-- Left Column: Big Headline -->
            <div class="w-full md:flex-1 md:max-w-2xl text-left">
                <h1 class="font-medium text-3xl sm:text-5xl lg:text-[56px] text-ink leading-[1.08] tracking-[-0.03em]">
                    Master the IT Passport exam, <span class="text-accent underline decoration-accent/30 underline-offset-4">one question</span> at a time.
                </h1>
            </div>

            <!-- Right Column: Subtitle & Action Buttons -->
            <div class="w-full md:w-[340px] lg:w-[380px] shrink-0 text-left space-y-5">
                <p class="text-sm sm:text-base text-stone leading-relaxed">
                    Topic practice with verified explanations, an automatic mistake review queue, and timed mock assessments.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-6 py-3 text-sm sm:text-base font-semibold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        <span>Start practicing</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <button @click="installApp()" type="button" class="inline-flex items-center space-x-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl px-5 py-3 text-sm sm:text-base font-medium transition-all shadow-xs hover:border-accent/40">
                        <svg class="w-4 h-4 shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Install app</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Feature Badges Grid -->
        <div class="mt-8 pt-6 border-t border-[#E3DFD5]/70 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs sm:text-sm text-stone font-medium">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>100% Free & Open</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Auto Mistake Queue</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Official ITPEC Syllabus</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Installable PWA App</span>
            </div>
        </div>
    </div>

    <!-- Interactive App Preview Window Container -->
    <x-welcome.app-preview />
</section>

