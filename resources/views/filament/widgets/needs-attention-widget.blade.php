<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <span class="text-base font-bold text-[#1F3A5F] dark:text-gray-100">Needs attention</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-xs text-[#6E6A60] dark:text-gray-400">Content and syllabus items requiring administrative review</span>
        </x-slot>

        <div class="space-y-3">
            @foreach($items as $item)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl border border-[#E3DFD5] dark:border-gray-800 bg-[#F6F4EF]/40 dark:bg-gray-900/40 gap-3">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-[#1F3A5F] dark:text-white">
                                {{ $item['title'] }}
                            </span>
                            <span @class([
                                'px-2 py-0.5 rounded-full text-xs font-mono font-bold',
                                'bg-[#F0F4F9] text-[#1F3A5F] border border-[#CBD7E7]' => $item['count'] === 0,
                                'bg-amber-100 text-amber-900 border border-amber-300' => $item['count'] > 0 && $item['severity'] === 'warning',
                                'bg-red-100 text-[#A3392F] border border-red-300' => $item['count'] > 0 && $item['severity'] === 'danger',
                            ])>
                                {{ number_format($item['count']) }}
                            </span>
                        </div>
                        <p class="text-xs text-[#6E6A60] dark:text-gray-400">
                            {{ $item['description'] }}
                        </p>
                    </div>

                    <div class="shrink-0">
                        <a href="{{ $item['link'] }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-gray-800 border border-[#E3DFD5] dark:border-gray-700 text-[#1F3A5F] dark:text-gray-200 hover:bg-[#F0F4F9] hover:border-[#1F3A5F] transition-colors">
                            <span>{{ $item['link_text'] }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
