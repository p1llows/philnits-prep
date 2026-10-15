<x-filament-widgets::widget>
    <div class="bg-white rounded-xl border border-[#E3DFD5] p-6 shadow-none h-full flex flex-col justify-between">
        <div>
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-[#6E6A60]">Needs attention</h3>
            </div>

            <div class="divide-y divide-[#E3DFD5]/60">
                @foreach($items as $item)
                    <div class="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#F0F4F9] flex items-center justify-center shrink-0 text-[#1F3A5F]">
                                <x-filament::icon
                                    icon="heroicon-o-document-duplicate"
                                    class="w-4 h-4 text-[#1F3A5F]"
                                />
                            </div>
                            <div>
                                <span class="text-xs font-medium text-[#1B1A17]">
                                    {{ $item['count'] }} {{ strtolower($item['title']) }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <a href="{{ $item['link'] }}" class="text-xs font-semibold text-[#1F3A5F] hover:underline underline-offset-2">
                                {{ $item['link_text'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
