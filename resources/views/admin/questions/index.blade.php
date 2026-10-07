<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Questions management') }}
            </h2>
            <a href="{{ route('admin.questions.create') }}" 
               class="px-4 py-2 bg-ink text-surface font-medium rounded-lg hover:bg-stone text-sm transition-colors">
                + Add new question
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-correct-surface border-l-4 border-correct text-correct rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters Bar -->
            <div class="bg-surface rounded-xl p-4 border border-line">
                <form action="{{ route('admin.questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="topic_id" class="block text-xs font-medium text-stone mb-1">Topic</label>
                        <select name="topic_id" id="topic_id" class="w-full rounded-lg border-line shadow-sm text-sm text-ink focus:border-accent focus:ring-accent">
                            <option value="">All topics</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }} ({{ $topic->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-medium text-stone mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-line shadow-sm text-sm text-ink focus:border-accent focus:ring-accent">
                            <option value="">All statuses</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validated</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-xs font-medium text-stone mb-1">Search</label>
                        <input type="text" name="search" id="search" placeholder="Search question text..." value="{{ request('search') }}"
                               class="w-full rounded-lg border-line shadow-sm text-sm text-ink focus:border-accent focus:ring-accent">
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-ink text-surface rounded-lg text-sm font-medium hover:bg-stone">
                            Filter
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-line text-stone rounded-lg text-sm font-medium hover:text-ink hover:bg-paper">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Questions Table -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="px-6 py-4 border-b border-line flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-ink">Question bank</h3>
                    <span class="text-xs text-stone">Total: {{ $questions->total() }} questions</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line">
                        <thead class="bg-paper">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone">ID / topic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone">Question text</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone">Ans</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-stone">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-stone">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-line">
                            @forelse($questions as $question)
                                <tr class="hover:bg-paper/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-xs text-stone">#{{ $question->source_question_number ?? $question->id }}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-accent-tint text-accent border border-accent/20 mt-1">
                                            {{ $question->topic->code ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-ink max-w-md">
                                        <p class="line-clamp-2 font-serif font-medium">{{ $question->question_text }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="w-7 h-7 rounded-lg bg-accent text-surface font-semibold flex items-center justify-center text-xs">
                                            {{ $question->correct_answer_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.questions.publish', $question) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $question->status === 'published' ? 'bg-correct-surface text-correct hover:opacity-90' : 'bg-accent-tint text-accent hover:opacity-90' }}">
                                                {{ ucfirst($question->status) }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.questions.show', $question) }}" class="text-accent hover:underline">View</a>
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-accent hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-stone">
                                        No questions found. Click "+ Add new question" to create your first question.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($questions->hasPages())
                    <div class="px-6 py-4 border-t border-line">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
