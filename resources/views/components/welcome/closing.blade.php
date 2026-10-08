<section class="py-12 sm:py-16 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-accent rounded-3xl px-8 sm:px-12 py-10 sm:py-14 relative overflow-hidden text-left shadow-xl hover:shadow-2xl transition-all duration-300 group">
        <!-- Navy Grid Pattern Background -->
        <svg aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full opacity-40 group-hover:opacity-50 transition-opacity duration-300">
            <defs>
                <pattern id="grid-navy" width="32" height="32" patternUnits="userSpaceOnUse">
                    <path d="M32 0H0V32" fill="none" stroke="#2D4C7A" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-navy)"/>
            <rect x="calc(100% - 128px)" y="32" width="32" height="32" fill="#2D4C7A"/>
            <rect x="calc(100% - 96px)" y="64" width="32" height="32" fill="#2D4C7A"/>
        </svg>

        <!-- Content -->
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <span class="inline-flex items-center space-x-2 bg-[#2D4C7A] text-[#A5B8D3] text-xs font-mono px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Ready to pass the IT Passport exam?</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-paper tracking-tight">
                    Start practicing today. One topic at a time.
                </h2>
                <p class="text-xs sm:text-sm text-[#A5B8D3]">
                    Created by Jewel Ramirez, ITPEC IT Passport certified. Free for all examinees.
                </p>
            </div>

            <div class="shrink-0 flex items-center space-x-3">
                <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 bg-paper text-accent hover:bg-surface hover:scale-[1.02] active:scale-[0.98] text-sm sm:text-base font-bold px-7 py-3.5 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-paper shadow-md">
                    <span>Create Free Account</span>
                    <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

