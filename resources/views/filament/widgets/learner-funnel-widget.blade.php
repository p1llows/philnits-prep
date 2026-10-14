<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <span class="text-base font-bold text-[#1F3A5F] dark:text-gray-100">Learner funnel</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-xs text-[#6E6A60] dark:text-gray-400">Progression from registration to active study</span>
        </x-slot>

        @if($biggestDrop)
            <div class="mb-5 p-3 rounded-xl bg-[#F0F4F9] dark:bg-gray-800/60 border border-[#CBD7E7] dark:border-gray-700 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-[#1F3A5F] dark:text-blue-300 font-medium">
                    <svg class="w-4 h-4 text-[#3A5F92]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                    <span>Biggest drop-off: <strong>{{ $biggestDrop['from'] }}</strong> &rarr; <strong>{{ $biggestDrop['to'] }}</strong></span>
                </div>
                <div class="font-mono text-[#6E6A60] dark:text-gray-400">
                    -{{ $biggestDrop['lost_count'] }} users ({{ $biggestDrop['pct_lost'] }}% drop)
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            @foreach($steps as $step)
                <div class="p-4 rounded-xl border border-[#E3DFD5] dark:border-gray-800 bg-[#F6F4EF]/50 dark:bg-gray-900/40 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] font-mono uppercase tracking-wider text-[#6E6A60] dark:text-gray-400 font-semibold truncate">
                            {{ $step['name'] }}
                        </div>
                        <div class="mt-2 text-2xl font-extrabold text-[#1F3A5F] dark:text-white tracking-tight">
                            {{ number_format($step['count']) }}
                        </div>
                        <div class="mt-0.5 text-xs font-semibold text-[#3A5F92] dark:text-blue-400">
                            {{ $step['percent_of_registered'] }}% of registered
                        </div>
                    </div>
                    <div class="mt-3 text-[11px] text-[#A29D91] dark:text-gray-500 line-clamp-1">
                        {{ $step['description'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
