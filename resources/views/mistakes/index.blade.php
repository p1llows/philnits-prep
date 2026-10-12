<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header with Paxi Companion Guidance -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>PAXI'S MISTAKE CLINIC</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight mb-1">Mistake Bank</h1>
                    <p class="text-xs sm:text-sm text-stone">
                        Review questions you missed previously. Once answered correctly, Paxi removes them from your bank!
                    </p>
                </div>

                <!-- Paxi Speech Bubble -->
                <div class="flex items-center space-x-3 bg-surface border border-[#E3DFD5] rounded-2xl p-3 sm:px-4 sm:py-3 shadow-xs shrink-0 max-w-xs">
                    <x-mascot variant="avatar" :size="40" class="w-10 h-10 rounded-full shrink-0 shadow-xs" label="Paxi companion" />
                    <div class="text-xs">
                        <p class="font-bold text-ink">Mistakes teach best!</p>
                        <p class="text-stone">Let's clear them one by one.</p>
                    </div>
                </div>
            </div>

            <!-- Mistake Review Livewire Component -->
            <livewire:mistake-review />

        </div>
    </div>
</x-app-layout>

