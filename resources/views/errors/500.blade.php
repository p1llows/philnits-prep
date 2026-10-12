<x-guest-layout>
    <div class="text-center space-y-4">
        <div class="flex justify-center">
            <x-mascot variant="oops" :size="160" />
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-ink">Something went wrong</h1>
            <p class="text-xs sm:text-sm text-stone mt-1.5 leading-relaxed">
                That was on our end. Please try again in a moment.
            </p>
        </div>
        <div class="pt-2">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <span>Return home</span>
            </a>
        </div>
    </div>
</x-guest-layout>
