<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ADMINISTRATION</span>
                        <span class="text-stone">·</span>
                        <span class="font-bold">TOPIC DETAIL</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">{{ $topic->name }}</h1>
                    <p class="text-sm text-stone">{{ $topic->code }} · Topic syllabus and question breakdown</p>
                </div>
                <div class="flex items-center space-x-3 shrink-0">
                    <a href="{{ route('admin.topics.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl text-xs font-medium transition-all shadow-xs">
                        <span>← Back to topics</span>
                    </a>
                    <a href="{{ route('admin.topics.edit', $topic) }}" class="inline-flex items-center space-x-1.5 px-5 py-2 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>Edit topic</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="p-4 bg-correct-surface/50 border border-correct/30 text-correct text-xs font-mono font-medium rounded-xl flex items-center shadow-xs">
                    <span class="mr-2 font-bold">✓</span> {{ session('success') }}
                </div>
            @endif

            <!-- Topic Meta Details -->
            <div class="bg-surface rounded-2xl p-6 border border-[#CFCABD] shadow-xs">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-3 mb-3">
                            <span class="font-mono text-xs font-bold px-3 py-1 rounded-full bg-paper text-accent border border-[#D9D5C9]">
                                {{ $topic->code ?? 'No code' }}
                            </span>
                            <span class="px-3 py-1 text-xs font-mono font-semibold rounded-full border {{ $topic->is_active ? 'bg-correct-surface/50 text-correct border-correct/30' : 'bg-paper text-stone border-[#D9D5C9]' }}">
                                {{ $topic->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-ink mb-2">{{ $topic->name }}</h3>
                        <p class="text-sm text-stone leading-relaxed">{{ $topic->description ?? 'No description.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Published Questions Section -->
            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-[#E3DFD5] bg-panel flex justify-between items-center">
                    <h4 class="text-base font-serif font-semibold text-ink">Associated questions</h4>
                    <span class="text-xs font-mono text-stone">{{ $topic->questions->count() }} published questions</span>
                </div>

                <div class="divide-y divide-[#E3DFD5]">
                    @forelse($topic->questions as $question)
                        <div class="p-6 hover:bg-paper/30 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <span class="font-mono font-bold text-xs text-accent">Question #{{ $question->source_question_number ?? $question->id }}</span>
                                <span class="text-xs font-mono text-stone uppercase">{{ ucfirst($question->status) }}</span>
                            </div>
                            <p class="font-serif text-ink text-sm leading-relaxed mb-3">{{ $question->question_text }}</p>
                            
                            @if($question->explanation)
                                <div class="p-4 bg-paper/60 rounded-xl text-xs text-stone border border-[#E3DFD5] leading-relaxed">
                                    <strong class="font-mono font-bold text-ink uppercase tracking-wider mr-1">Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-xs text-stone">
                            No published questions assigned to this topic yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
