<x-filament-widgets::widget>
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-none">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Learner funnel</h3>
                <p class="text-xs text-gray-500 mt-0.5">Where new learners drop off on the way to studying</p>
            </div>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-primary-100 text-primary-700 font-medium">
                {{ number_format($totalRegistered) }} {{ Str::plural('learner', $totalRegistered) }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach($steps as $step)
                <div class="p-3.5 rounded-lg border border-gray-100 bg-gray-50 flex flex-col justify-between">
                    <div>
                        <div class="text-[13px] text-gray-500">
                            {{ $step['name'] }}
                        </div>
                        <div class="mt-2 text-[28px] font-semibold text-gray-900 leading-tight">
                            {{ number_format($step['count']) }}
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-primary-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $step['percent_of_registered'])) }}%"></div>
                        </div>

                        <div class="mt-2 flex items-center justify-between text-xs">
                            <span class="text-gray-400">Conversion</span>
                            <span class="font-medium text-gray-700">{{ $step['percent_of_registered'] }}%</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($biggestDrop)
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-2 text-xs text-gray-600">
                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">!</span>
                <span>
                    <strong>Biggest drop:</strong> {{ strtolower($biggestDrop['from']) }} &rarr; {{ strtolower($biggestDrop['to']) }}
                    <span class="text-danger-600 font-semibold">(-{{ $biggestDrop['lost_count'] }} {{ Str::plural('learner', $biggestDrop['lost_count']) }}, {{ $biggestDrop['pct_lost'] }}% loss)</span>
                </span>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
