<x-filament-widgets::widget>
    @php
        $activeItems = collect($items)->filter(fn ($item) => ($item['count'] ?? 0) > 0);
    @endphp

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-none h-full flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Needs attention</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Curriculum & question bank health checks</p>
                </div>
            </div>

            @if($activeItems->isEmpty())
                <div class="py-8 text-center text-sm text-gray-500">
                    Nothing needs attention right now.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($activeItems as $item)
                        <div class="py-3 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div @class([
                                    'w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-xs font-semibold',
                                    'bg-primary-50 text-primary-700' => ($item['severity'] ?? '') === 'warning',
                                    'bg-danger-50 text-danger-700' => ($item['severity'] ?? '') === 'danger',
                                    'bg-gray-100 text-gray-700' => !in_array($item['severity'] ?? '', ['warning', 'danger']),
                                ])>
                                    {{ $item['count'] }}
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-medium text-gray-900 truncate">
                                        {{ $item['title'] }}
                                    </div>
                                    <div class="text-[11px] text-gray-500 truncate">
                                        {{ $item['description'] }}
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <a href="{{ $item['link'] }}" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-2.5 py-1 rounded-md transition-colors">
                                    {{ $item['link_text'] }} &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
