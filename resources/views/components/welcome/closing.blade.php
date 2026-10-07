<section class="p-5 sm:px-8 max-w-[1100px] mx-auto">
    <div class="bg-accent rounded-[12px] px-[28px] py-[34px] relative overflow-hidden text-left">
        <!-- Navy Grid Pattern Background -->
        <svg aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full opacity-40">
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
                <h2 class="text-[26px] font-medium text-paper tracking-tight mb-1">Start with one topic.</h2>
                <p class="text-xs text-[#A5B8D3]">Made by Jewel Ramirez, ITPEC IT Passport certified.</p>
            </div>

            <div>
                <a href="{{ route('register') }}" class="inline-block bg-paper text-accent hover:bg-surface text-sm font-medium px-5 py-2.5 rounded-[8px] transition-colors focus:outline-none focus:ring-2 focus:ring-paper focus:ring-offset-2 focus:ring-offset-accent">
                    Create an account
                </a>
            </div>
        </div>
    </div>
</section>
