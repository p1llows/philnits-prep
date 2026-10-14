<section x-data="pwaInstall" class="relative pt-20 sm:pt-24 lg:pt-28 pb-12 sm:pb-16 lg:pb-24">
    <!-- Graph Paper Grid Background with subtle radial fade -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
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

    <!-- Fixed Top Navbar with Mobile Menu Support -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 z-50 w-full bg-paper/90 backdrop-blur-md border-b border-[#E3DFD5] transition-all duration-200">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2.5 text-accent font-medium text-base shrink-0 group" aria-label="Paxi Home">
                <img src="/paxi-mark.svg" width="24" height="24" alt="" class="h-6 w-auto shrink-0 transition-transform duration-200 group-hover:scale-105">
                <span class="font-extrabold text-accent text-xl tracking-tight">Paxi</span>
            </a>

            <div class="hidden md:flex items-center space-x-6 lg:space-x-8 text-sm font-medium">
                <a href="#study-loop" class="text-stone hover:text-ink transition-colors duration-200">Study Loop</a>
                <a href="#fields" class="text-stone hover:text-ink transition-colors duration-200">Exam Fields</a>
                <a href="#faq" class="text-stone hover:text-ink transition-colors duration-200">FAQ</a>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3 text-xs sm:text-sm">
                <button @click="installApp()" type="button" class="hidden sm:inline-flex items-center space-x-1.5 border border-[#D9D5C9] bg-surface hover:bg-paper text-accent px-3 py-2 sm:px-3.5 sm:py-2 rounded-xl font-medium transition-all shadow-xs hover:border-accent/40">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Install App</span>
                </button>
                <a href="{{ route('login') }}" class="text-stone hover:text-ink transition-colors font-medium px-2.5 py-2 sm:px-3">Log in</a>
                <a href="{{ route('register') }}" class="bg-accent text-surface hover:bg-accent/90 rounded-xl px-4 py-2 sm:px-5 sm:py-2 font-medium transition-all shadow-sm shrink-0 hover:shadow whitespace-nowrap">
                    Get Started
                </a>
                <!-- Mobile Navigation Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-stone hover:text-ink hover:bg-surface focus:outline-none transition-colors" aria-label="Toggle navigation menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-[#E3DFD5] bg-paper px-4 py-3 space-y-1 shadow-sm">
            <a @click="mobileMenuOpen = false" href="#study-loop" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone hover:text-ink hover:bg-surface">Study Loop</a>
            <a @click="mobileMenuOpen = false" href="#fields" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone hover:text-ink hover:bg-surface">Exam Fields</a>
            <a @click="mobileMenuOpen = false" href="#faq" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone hover:text-ink hover:bg-surface">FAQ</a>
            <button @click="installApp(); mobileMenuOpen = false" type="button" class="w-full text-left sm:hidden flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-accent hover:bg-surface">
                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Install PWA App</span>
            </button>
        </div>
    </nav>

    <!-- Split Headline Row -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2 sm:mt-4 mb-6 sm:mb-10 lg:mb-12">
        <!-- Eyebrow Pill -->
        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-[#D9D5C9] bg-surface/90 text-accent font-mono text-xs mb-4 sm:mb-6 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
            <span>Paxi · IT Passport Study Companion</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 sm:gap-8 lg:gap-12">
            <!-- Left Column: Big Headline -->
            <div class="w-full lg:flex-1 lg:max-w-2xl text-left">
                <h1 class="font-medium text-3xl sm:text-4xl md:text-5xl lg:text-[50px] xl:text-[56px] text-ink leading-[1.12] sm:leading-[1.1] lg:leading-[1.08] tracking-[-0.03em]">
                    Master the IT Passport exam, <span class="text-accent underline decoration-accent/30 underline-offset-4">one question</span> at a time.
                </h1>
            </div>

            <!-- Right Column: Subtitle & Action Buttons -->
            <div class="w-full lg:w-[340px] xl:w-[380px] shrink-0 text-left space-y-4 sm:space-y-5">
                <p class="text-sm sm:text-base text-stone leading-relaxed">
                    Topic practice with verified explanations, an automatic mistake review queue, and timed mock assessments.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 bg-accent text-surface hover:bg-accent/90 rounded-xl px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base font-semibold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        <span>Start practicing</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <button @click="installApp()" type="button" class="inline-flex items-center space-x-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl px-4 sm:px-5 py-2.5 sm:py-3 text-sm sm:text-base font-medium transition-all shadow-xs hover:border-accent/40">
                        <svg class="w-4 h-4 shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Install app</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Feature Badges Grid -->
        <div class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-[#E3DFD5]/70 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs sm:text-sm text-stone font-medium">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="truncate">100% Free & Open</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="truncate">Auto Mistake Queue</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="truncate">Official ITPEC Syllabus</span>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="truncate">Installable PWA App</span>
            </div>
        </div>
    </div>

    <!-- Interactive App Preview Window Container -->
    <x-welcome.app-preview />
</section>

