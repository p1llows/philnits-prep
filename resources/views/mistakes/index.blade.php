<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header with Big Paxi Inspect Mascot -->
            <div class="relative bg-surface rounded-3xl border border-[#D9D5C9] p-6 sm:p-8 shadow-xs overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="space-y-2.5 max-w-xl">
                        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs font-medium shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                            <span>PAXI MISTAKE CLINIC</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">Mistake Bank</h1>
                        <p class="text-xs sm:text-sm text-stone leading-relaxed">
                            Review questions you missed previously. Once answered correctly, Paxi removes them from your active mistake list!
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center justify-center shrink-0">
                        <x-mascot variant="inspect" :size="110" class="w-24 h-24 sm:w-28 sm:h-28 drop-shadow-md" />
                    </div>
                </div>
            </div>

            <!-- Mistake Review Livewire Component -->
            <livewire:mistake-review />

        </div>
    </div>
</x-app-layout>

