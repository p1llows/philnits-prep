<x-guest-layout>
    <div class="text-center space-y-4 py-4">
        <div class="flex justify-center mb-2">
            <x-mascot variant="oops" :size="140" label="Paxi Error" />
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-ink tracking-tight">Something went wrong</h1>
            <p class="text-xs sm:text-sm text-stone mt-1.5 max-w-sm mx-auto leading-relaxed">
                We couldn't load your data right now. Please try again.
            </p>
        </div>
        <div class="pt-4 flex items-center justify-center gap-3">
            <button onclick="window.location.reload()" type="button" class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs sm:text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Retry</span>
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2.5 border border-[#D9D5C9] bg-surface text-stone hover:text-ink hover:bg-paper rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs">
                <span>Dashboard</span>
            </a>
        </div>
    </div>
</x-guest-layout>
