<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Topic detail') }}: {{ $topic->name }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('admin.topics.edit', $topic) }}" class="px-4 py-2 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
                    Edit topic
                </a>
                <a href="{{ route('admin.topics.index') }}" class="text-sm text-stone hover:text-ink">
                    ← Back to topics
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-correct-surface border-l-4 border-correct text-correct rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Topic Meta Details -->
            <div class="bg-surface rounded-xl p-6 border border-line border-l-4 border-l-accent">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="font-mono text-xs font-semibold px-2 py-0.5 rounded bg-paper text-ink border border-line">
                                {{ $topic->code ?? 'No code' }}
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $topic->is_active ? 'bg-correct-surface text-correct' : 'bg-paper text-stone border border-line' }}">
                                {{ $topic->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-ink mb-2">{{ $topic->name }}</h3>
                        <p class="text-stone leading-relaxed">{{ $topic->description ?? 'No description.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Published Questions Section -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="px-6 py-4 border-b border-line flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-ink">Associated questions</h4>
                    <span class="text-xs text-stone">{{ $topic->questions->count() }} published questions</span>
                </div>

                <div class="divide-y divide-line">
                    @forelse($topic->questions as $question)
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <span class="font-semibold text-sm text-accent">Question #{{ $question->source_question_number ?? $question->id }}</span>
                                <span class="text-xs text-stone">Status: {{ ucfirst($question->status) }}</span>
                            </div>
                            <p class="font-serif text-ink font-medium mb-3">{{ $question->question_text }}</p>
                            
                            @if($question->explanation)
                                <div class="p-3 bg-paper rounded-lg text-xs text-stone border border-line">
                                    <strong>Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-stone">
                            No published questions assigned to this topic yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
