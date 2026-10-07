<section class="py-10 sm:py-14 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-accent rounded-2xl px-8 sm:px-12 py-10 sm:py-12 relative overflow-hidden text-left shadow-lg hover:shadow-2xl transition-all duration-300 group">
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
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-medium text-paper tracking-tight mb-2">Start with one topic.</h2>
                <p class="text-xs sm:text-sm text-[#A5B8D3]">Made by Jewel Ramirez, ITPEC IT Passport certified.</p>
            </div>

            <div>
                <a href="{{ route('register') }}" class="inline-block bg-paper text-accent hover:bg-surface hover:scale-[1.03] active:scale-[0.98] text-sm sm:text-base font-medium px-6 py-3 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-paper focus:ring-offset-2 focus:ring-offset-accent shadow-sm">
                    Create an account
                </a>
            </div>
        </div>
    </div>
</section>
