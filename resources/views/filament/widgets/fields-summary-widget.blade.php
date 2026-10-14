<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        @foreach($fields as $key => $info)
            <div class="p-4 rounded-xl border border-[#E3DFD5] dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xs flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-[#1F3A5F] dark:text-gray-200">
                        {{ $info['name'] }}
                    </div>
                    <div class="mt-1 text-2xl font-extrabold text-[#1F3A5F] dark:text-white">
                        {{ $info['topics_count'] }} <span class="text-xs font-normal text-[#6E6A60] dark:text-gray-400">topics</span>
                    </div>
                    <div class="text-[11px] text-[#6E6A60] dark:text-gray-400">
                        {{ $info['active_topics'] }} active
                    </div>
                </div>

                @if($info['weight'])
                    <div class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-[#F0F4F9] text-[#1F3A5F] border border-[#CBD7E7]">
                        Weight: {{ $info['weight'] }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
