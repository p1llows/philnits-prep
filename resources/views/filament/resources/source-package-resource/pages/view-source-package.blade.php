<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-[#6E6A60] dark:text-gray-400 font-medium">Session:</span>
                    <span class="font-bold text-[#1F3A5F] dark:text-gray-200 ml-1">{{ $record->exam_session_label ?: 'None' }}</span>
                </div>
                <div>
                    <span class="text-[#6E6A60] dark:text-gray-400 font-medium">Use:</span>
                    <span class="font-bold text-[#1F3A5F] dark:text-gray-200 ml-1">{{ ucfirst($record->use) }}</span>
                </div>
                <div>
                    <span class="text-[#6E6A60] dark:text-gray-400 font-medium">Paper PDF:</span>
                    <span class="font-bold ml-1 {{ $record->paper_path ? 'text-[#2F6B4F]' : 'text-[#6E6A60]' }}">
                        {{ $record->paper_path ? 'Uploaded' : 'None' }}
                    </span>
                </div>
                <div>
                    <span class="text-[#6E6A60] dark:text-gray-400 font-medium">Official answer key:</span>
                    <span class="font-bold ml-1 {{ $record->answer_key_path ? 'text-[#2F6B4F]' : 'text-[#A3392F]' }}">
                        {{ $record->answer_key_path ? 'Uploaded' : 'Missing' }}
                    </span>
                </div>
            </div>
            @if($record->notes)
                <div class="mt-3 pt-3 border-t border-[#E3DFD5] dark:border-gray-800 text-xs text-[#6E6A60]">
                    {{ $record->notes }}
                </div>
            @endif
        </x-filament::section>

        <div>
            <h3 class="text-sm font-bold text-[#1F3A5F] dark:text-gray-200 mb-3">Package questions</h3>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
