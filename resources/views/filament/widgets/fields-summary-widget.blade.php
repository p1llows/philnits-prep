<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        @foreach($fields as $key => $info)
            <div class="p-4 rounded-xl border border-[#E3DFD5] bg-white shadow-2xs flex items-center justify-between hover:border-[#CBD7E7] transition-all">
                <div>
                    <div class="text-xs font-bold text-[#1F3A5F] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $key === 'strategy' ? 'bg-[#3A5F92]' : ($key === 'management' ? 'bg-amber-600' : 'bg-[#1F3A5F]') }}"></span>
                        {{ $info['name'] }}
                    </div>
                    <div class="mt-1 text-2xl font-extrabold text-[#1F3A5F]">
                        {{ $info['topics_count'] }} <span class="text-xs font-normal text-[#6E6A60]">topics</span>
                    </div>
                    <div class="text-[11px] text-[#6E6A60] mt-0.5">
                        {{ $info['active_topics'] }} active in curriculum
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
