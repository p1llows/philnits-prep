<x-filament-widgets::widget>
    <div class="bg-white rounded-xl border border-[#E3DFD5] p-6 shadow-none">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-[#6E6A60]">Learner funnel</h3>
            <p class="text-xs text-[#A29D91]">Where new learners drop off on the way to studying</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            @foreach($steps as $step)
                <div class="p-4 rounded-xl border border-[#E3DFD5] bg-white flex flex-col justify-between">
                    <div>
                        <div class="text-xs font-normal text-[#6E6A60]">
                            {{ $step['name'] }}
                        </div>
                        <div class="mt-2 text-2xl font-bold text-[#1B1A17] tracking-tight">
                            {{ number_format($step['count']) }}
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3 w-full bg-[#EFECE4] rounded-full h-1 overflow-hidden">
                            <div class="bg-[#1F3A5F] h-1 rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $step['percent_of_registered'])) }}%"></div>
                        </div>

                        <div class="mt-1.5 text-[11px] font-medium text-[#6E6A60]">
                            {{ $step['percent_of_registered'] }}%
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($biggestDrop)
            <div class="mt-4 pt-3 border-t border-[#E3DFD5]/60 text-xs text-[#6E6A60]">
                Biggest drop: {{ strtolower($biggestDrop['from']) }} to {{ strtolower($biggestDrop['to']) }} ({{ $biggestDrop['lost_count'] }} people).
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
