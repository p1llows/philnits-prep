<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Pipeline Strip -->
        @php
            $counts = $this->getPipelineCounts();
            $packages = \App\Models\SourcePackage::orderBy('name')->pluck('name', 'id');
            $currentQ = $this->getCurrentQuestion();
            $topics = \App\Models\Topic::active()->ordered()->get();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="p-3.5 rounded-xl border border-[#E3DFD5] bg-white shadow-2xs">
                <div class="text-[11px] font-mono uppercase text-[#6E6A60] font-semibold">Uploaded packages</div>
                <div class="mt-1 text-xl font-extrabold text-[#1F3A5F]">{{ number_format($counts['uploaded']) }}</div>
            </div>
            <div class="p-3.5 rounded-xl border border-[#E3DFD5] bg-white shadow-2xs">
                <div class="text-[11px] font-mono uppercase text-[#6E6A60] font-semibold">Parsed (drafts)</div>
                <div class="mt-1 text-xl font-extrabold text-[#1F3A5F]">{{ number_format($counts['parsed']) }}</div>
            </div>
            <div class="p-3.5 rounded-xl border border-[#CBD7E7] bg-[#F0F4F9] shadow-2xs">
                <div class="text-[11px] font-mono uppercase text-[#1F3A5F] font-bold">In review</div>
                <div class="mt-1 text-xl font-extrabold text-[#1F3A5F]">{{ number_format($counts['in_review']) }}</div>
            </div>
            <div class="p-3.5 rounded-xl border border-[#E3DFD5] bg-white shadow-2xs">
                <div class="text-[11px] font-mono uppercase text-[#6E6A60] font-semibold">Published</div>
                <div class="mt-1 text-xl font-extrabold text-[#2F6B4F]">{{ number_format($counts['published']) }}</div>
            </div>
        </div>

        <!-- Filter & Pagination Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border border-[#E3DFD5] bg-white shadow-2xs">
            <div class="flex items-center gap-3">
                <label for="packageFilter" class="text-xs font-semibold text-[#6E6A60] shrink-0">Package filter:</label>
                <select id="packageFilter" wire:model.live="selectedPackageId" class="text-xs rounded-lg border-[#E3DFD5] bg-[#FAF9F5] text-[#1F3A5F] py-1.5 px-3 focus:ring-[#1F3A5F] focus:border-[#1F3A5F]">
                    <option value="">All source packages</option>
                    @foreach($packages as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @if($this->questionsCount > 0)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-mono font-medium text-[#6E6A60]">
                        Question {{ $currentIndex + 1 }} of {{ $this->questionsCount }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" wire:click="previousQuestion" @disabled($currentIndex === 0) class="px-2.5 py-1 text-xs font-medium rounded-lg border border-[#E3DFD5] bg-white text-[#1F3A5F] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F0F4F9] transition-colors">
                            &larr; Previous
                        </button>
                        <button type="button" wire:click="nextQuestion" @disabled($currentIndex >= $this->questionsCount - 1) class="px-2.5 py-1 text-xs font-medium rounded-lg border border-[#E3DFD5] bg-white text-[#1F3A5F] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#F0F4F9] transition-colors">
                            Next &rarr;
                        </button>
                    </div>
                </div>
            @endif
        </div>

        @if(!$currentQ)
            <div class="p-12 text-center rounded-2xl border border-dashed border-[#E3DFD5] bg-white flex flex-col items-center justify-center shadow-2xs">
                <div class="w-12 h-12 rounded-full bg-[#FAF9F5] border border-[#E3DFD5] flex items-center justify-center text-[#2F6B4F] mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#1F3A5F]">No questions pending review</h3>
                <p class="text-xs text-[#6E6A60] mt-1 max-w-sm">All extracted questions have been processed or none match the selected package filter.</p>
                <a href="{{ route('filament.admin.resources.questions.index') }}" class="mt-4 px-3 py-1.5 text-xs font-semibold rounded-lg bg-[#1F3A5F] text-white hover:bg-[#152842] transition-colors">
                    View question bank &rarr;
                </a>
            </div>
        @else
            <!-- Split 2-Column Review Interface -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Card: Original Page (5 cols) -->
                <div class="lg:col-span-5 flex flex-col rounded-2xl border border-[#E3DFD5] bg-white overflow-hidden shadow-2xs">
                    <div class="px-4 py-3 border-b border-[#E3DFD5] bg-[#FAF9F5] flex items-center justify-between">
                        <span class="text-xs font-bold text-[#1F3A5F]">Original booklet page</span>
                        <span class="text-[11px] font-mono text-[#6E6A60]">
                            PDF page {{ $currentQ->source_page ?: 'N/A' }} · Q{{ $currentQ->source_question_number ?: '?' }}
                        </span>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-center items-center min-h-[500px] bg-[#FAF9F5]">
                        @if($this->getPdfPreviewUrl())
                            <iframe src="{{ $this->getPdfPreviewUrl() }}" class="w-full h-full min-h-[550px] border-0 rounded-lg shadow-inner"></iframe>
                        @else
                            <div class="text-center p-8 space-y-2">
                                <svg class="w-8 h-8 text-[#A29D91] mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-xs text-[#6E6A60] font-medium">No original PDF or page reference attached to this question.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Card: Extracted Question (7 cols) -->
                <div class="lg:col-span-7 flex flex-col rounded-2xl border border-[#E3DFD5] bg-white shadow-2xs p-5 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E3DFD5]">
                        <span class="text-xs font-bold text-[#1F3A5F] uppercase tracking-wider font-mono">
                            Extracted question
                        </span>
                        @if($currentQ->review_note)
                            <span class="text-[11px] px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-medium">
                                Previous note: {{ $currentQ->review_note }}
                            </span>
                        @endif
                    </div>

                    <!-- Question Text -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1F3A5F]">Question text</label>
                        <textarea wire:model="question_text" rows="3" class="w-full text-xs rounded-xl border-[#E3DFD5] bg-white text-gray-900 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]"></textarea>
                    </div>

                    <!-- Code block -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-[#1F3A5F]">Code block (optional monospace snippet)</label>
                        <textarea wire:model="code_block" rows="2" placeholder="Paste pseudocode, algorithm, or SQL snippet..." class="w-full font-mono text-xs rounded-xl border-[#E3DFD5] bg-gray-900 text-gray-100 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]"></textarea>
                    </div>

                    <!-- Choices A to D with Radio for Correct One -->
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-[#1F3A5F]">Choices (select correct radio)</label>
                        @foreach(['A', 'B', 'C', 'D'] as $code)
                            <div class="flex items-center gap-2 p-1.5 rounded-lg border border-[#EFECE4] hover:bg-[#FAF9F5] transition-colors">
                                <input type="radio" wire:model="correct_answer_code" name="correct_choice" value="{{ $code }}" id="choice_radio_{{ $code }}" class="text-[#1F3A5F] focus:ring-[#1F3A5F] h-4 w-4 border-gray-300">
                                <label for="choice_radio_{{ $code }}" class="w-6 text-xs font-mono font-bold text-[#1F3A5F] cursor-pointer">{{ $code }}</label>
                                <input type="text" wire:model="choice_{{ $code }}" class="flex-1 text-xs rounded-md border-[#E3DFD5] bg-white text-gray-900 py-1.5 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]">
                            </div>
                        @endforeach
                    </div>

                    <!-- Classification: Answer Source, Field, Topic -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1F3A5F]">Answer source</label>
                            <select wire:model="answer_source" class="w-full text-xs rounded-lg border-[#E3DFD5] bg-white text-gray-900 py-1.5 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]">
                                <option value="official_key">Official key</option>
                                <option value="model_suggestion">Model suggestion</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-[#1F3A5F]">Syllabus topic & field</label>
                            <select wire:model="topic_id" class="w-full text-xs rounded-lg border-[#E3DFD5] bg-white text-gray-900 py-1.5 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]">
                                <option value="">Select topic</option>
                                @foreach($topics as $t)
                                    <option value="{{ $t->id }}">[{{ ucfirst($t->field) }}] {{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Explanation -->
                    <div class="space-y-1 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold text-[#1F3A5F]">Explanation rationale</label>
                            @if($explanation_status === 'ai_draft')
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[#E6ECF4] text-[#1F3A5F]">
                                    AI draft · not approved
                                </span>
                            @endif
                        </div>
                        <textarea wire:model="explanation" rows="2" placeholder="Write question rationale..." class="w-full text-xs rounded-xl border-[#E3DFD5] bg-white text-gray-900 focus:border-[#1F3A5F] focus:ring-[#1F3A5F]"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-[#E3DFD5]">
                        <button type="button" wire:click="saveDraft" class="px-3.5 py-1.5 text-xs font-medium rounded-lg border border-[#E3DFD5] text-[#6E6A60] hover:bg-[#F6F4EF] transition-colors">
                            Save draft
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="reject" class="px-3 py-1.5 text-xs font-medium rounded-lg text-[#A3392F] hover:bg-red-50 transition-colors">
                                Reject
                            </button>
                            <button type="button" wire:click="openNeedsFixModal" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-amber-300 text-amber-900 bg-amber-50 hover:bg-amber-100 transition-colors">
                                Needs fix
                            </button>
                            <button type="button" wire:click="approve" class="px-4 py-1.5 text-xs font-bold rounded-lg bg-[#2F6B4F] text-white hover:bg-[#25563F] shadow-xs transition-colors">
                                Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Confirmation Modal for Manual Answer Source -->
    <x-filament::modal id="manual-confirm-modal" :visible="$showManualConfirmModal" width="md">
        <x-slot name="heading">Confirm manual answer verification</x-slot>
        <x-slot name="description">
            This question has answer source set to <strong>Manual</strong>. Please confirm you have independently checked the correct answer code before approving.
        </x-slot>
        <x-slot name="footer">
            <x-filament::button color="gray" wire:click="$set('showManualConfirmModal', false)">
                Cancel
            </x-filament::button>
            <x-filament::button color="success" wire:click="confirmManualApproval">
                Approve manually
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <!-- Needs Fix Modal -->
    <x-filament::modal id="needs-fix-modal" :visible="$showNeedsFixModal" width="md">
        <x-slot name="heading">Needs fix note</x-slot>
        <div class="space-y-2 py-2">
            <label class="text-xs font-semibold text-[#1F3A5F]">Specify what needs correction:</label>
            <textarea wire:model="needs_fix_note" rows="3" class="w-full text-xs rounded-xl border-[#E3DFD5] p-2 focus:ring-[#1F3A5F]"></textarea>
        </div>
        <x-slot name="footer">
            <x-filament::button color="gray" wire:click="$set('showNeedsFixModal', false)">
                Cancel
            </x-filament::button>
            <x-filament::button color="warning" wire:click="submitNeedsFix">
                Submit note
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>
