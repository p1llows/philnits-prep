<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ADMINISTRATION</span>
                        <span class="text-stone">·</span>
                        <span class="font-bold">QUESTION BANK</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Questions management</h1>
                    <p class="text-sm text-stone">Maintain examination questions, choices, answers, and explanations.</p>
                </div>
                <a href="{{ route('admin.questions.create') }}" 
                   class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add new question</span>
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-correct-surface/50 border border-correct/30 text-correct text-xs font-mono font-medium rounded-xl flex items-center shadow-xs">
                    <span class="mr-2 font-bold">✓</span> {{ session('success') }}
                </div>
            @endif

            <!-- Filters Bar -->
            <div class="bg-surface rounded-2xl p-5 border border-[#CFCABD] shadow-xs">
                <form action="{{ route('admin.questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="topic_id" class="block text-xs font-mono font-medium text-stone uppercase mb-1.5">Topic</label>
                        <select name="topic_id" id="topic_id" class="w-full rounded-xl border-[#D9D5C9] shadow-xs text-xs text-ink focus:border-accent focus:ring-accent">
                            <option value="">All topics</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }} ({{ $topic->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-mono font-medium text-stone uppercase mb-1.5">Status</label>
                        <select name="status" id="status" class="w-full rounded-xl border-[#D9D5C9] shadow-xs text-xs text-ink focus:border-accent focus:ring-accent">
                            <option value="">All statuses</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validated</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-xs font-mono font-medium text-stone uppercase mb-1.5">Search</label>
                        <input type="text" name="search" id="search" placeholder="Search question text..." value="{{ request('search') }}"
                               class="w-full rounded-xl border-[#D9D5C9] shadow-xs text-xs text-ink focus:border-accent focus:ring-accent">
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-accent text-surface rounded-xl text-xs font-semibold hover:bg-accent/90 transition-all shadow-xs">
                            Filter
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-[#D9D5C9] text-stone rounded-xl text-xs font-medium hover:text-ink hover:bg-paper transition-colors shadow-xs">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Questions Table -->
            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-[#E3DFD5] bg-panel flex justify-between items-center">
                    <h3 class="text-base font-serif font-bold text-ink">Question bank</h3>
                    <span class="text-xs font-mono text-stone">Total: {{ $questions->total() }} questions</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E3DFD5]">
                        <thead class="bg-panel">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">ID / topic</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Question text</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Ans</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-mono font-medium text-stone uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-[#E3DFD5]">
                            @forelse($questions as $question)
                                <tr class="hover:bg-paper/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-xs font-bold text-ink">#{{ $question->source_question_number ?? $question->id }}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-mono bg-paper border border-[#D9D5C9] text-accent mt-1">
                                            {{ $question->topic->code ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-ink max-w-md">
                                        <p class="line-clamp-2 font-serif">{{ $question->question_text }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="w-7 h-7 rounded-lg bg-accent text-surface font-mono font-bold flex items-center justify-center text-xs shadow-xs">
                                            {{ $question->correct_answer_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.questions.publish', $question) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs font-mono font-semibold rounded-full border {{ $question->status === 'published' ? 'bg-correct-surface/50 text-correct border-correct/30' : 'bg-paper text-stone border-[#D9D5C9]' }} transition-colors shadow-xs">
                                                {{ ucfirst($question->status) }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                        <a href="{{ route('admin.questions.show', $question) }}" class="inline-flex items-center px-3 py-1 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-lg text-xs font-medium transition-all shadow-xs">View</a>
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center px-3 py-1 bg-accent text-surface hover:bg-accent/90 rounded-lg text-xs font-semibold shadow-xs">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-xs text-stone">
                                        No questions found. Click "+ Add new question" to create your first question.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($questions->hasPages())
                    <div class="px-6 py-4 border-t border-[#E3DFD5] bg-panel">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
