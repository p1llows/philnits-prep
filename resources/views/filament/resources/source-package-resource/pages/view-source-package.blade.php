<x-filament-panels::page>
    @php
        $record = $record ?? $this->record;
    @endphp
    <div class="space-y-6">
        <x-filament::section>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="p-3.5 rounded-lg bg-[#FAF9F5] border border-[#EFECE4]">
                    <span class="text-[#6E6A60] font-semibold uppercase text-[10px] tracking-wider block">Exam session</span>
                    <span class="font-bold text-[#1F3A5F] text-sm mt-1 block">{{ $record->exam_session_label ?: 'None specified' }}</span>
                </div>
                <div class="p-3.5 rounded-lg bg-[#FAF9F5] border border-[#EFECE4]">
                    <span class="text-[#6E6A60] font-semibold uppercase text-[10px] tracking-wider block">Intended use</span>
                    <span class="font-bold text-[#1F3A5F] text-sm mt-1 block">{{ ucfirst($record->use) }}</span>
                </div>
                <div class="p-3.5 rounded-lg bg-[#FAF9F5] border border-[#EFECE4]">
                    <span class="text-[#6E6A60] font-semibold uppercase text-[10px] tracking-wider block">Paper booklet PDF</span>
                    <span class="font-bold text-sm mt-1 block {{ $record->paper_path ? 'text-[#2F6B4F]' : 'text-[#6E6A60]' }}">
                        {{ $record->paper_path ? '✓ Uploaded' : '— Not attached' }}
                    </span>
                </div>
                <div class="p-3.5 rounded-lg bg-[#FAF9F5] border border-[#EFECE4]">
                    <span class="text-[#6E6A60] font-semibold uppercase text-[10px] tracking-wider block">Official answer key</span>
                    <span class="font-bold text-sm mt-1 block {{ $record->answer_key_path ? 'text-[#2F6B4F]' : 'text-[#A3392F]' }}">
                        {{ $record->answer_key_path ? '✓ Uploaded' : '✕ Missing' }}
                    </span>
                </div>
            </div>
            @if($record->notes)
                <div class="mt-3 pt-3 border-t border-[#E3DFD5] text-xs text-[#6E6A60]">
                    <strong>Notes:</strong> {{ $record->notes }}
                </div>
            @endif
        </x-filament::section>

        <div>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-bold text-[#1F3A5F]">Package questions</h3>
                    <p class="text-xs text-[#6E6A60]">Questions extracted from this examination booklet</p>
                </div>
            </div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
