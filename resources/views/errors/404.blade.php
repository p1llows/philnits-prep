<x-guest-layout>
    <div class="text-center space-y-4 py-4">
        <div class="flex justify-center mb-2">
            <x-mascot variant="oops" :size="160" />
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-ink tracking-tight">Page not found</h1>
            <p class="text-xs sm:text-sm text-stone mt-1.5 max-w-sm mx-auto leading-relaxed">
                We couldn't find the page you were looking for.
            </p>
        </div>
        <div class="pt-4 flex items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs sm:text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <span>Back to Paxi</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</x-guest-layout>
