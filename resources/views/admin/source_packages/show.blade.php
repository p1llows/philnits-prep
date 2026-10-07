<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-2 text-sm text-stone mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-ink transition-colors">Admin</a>
                        <span>/</span>
                        <a href="{{ route('admin.source-packages.index') }}" class="hover:text-ink transition-colors">Source packages</a>
                        <span>/</span>
                        <span class="text-ink font-medium">{{ $sourcePackage->name }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-bold text-ink">{{ $sourcePackage->name }}</h1>
                        @if($sourcePackage->status === 'published')
                            <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-correct-light text-correct">
                                Published
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-accent-light text-accent">
                                {{ ucfirst($sourcePackage->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.source-packages.edit', $sourcePackage) }}" 
                       class="px-4 py-2 bg-surface border border-line text-ink font-medium text-sm rounded-lg hover:bg-paper/50 transition-colors">
                        Edit details
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-correct-light/60 border border-correct/30 text-correct text-sm rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-wrong-light/60 border border-wrong/30 text-wrong text-sm rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Package Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-surface border border-line rounded-xl p-4 text-center">
                    <p class="text-xs font-medium text-stone">Total questions</p>
                    <p class="text-2xl font-bold text-ink mt-1">{{ $sourcePackage->questions->count() }}</p>
                </div>
                <div class="bg-surface border border-line rounded-xl p-4 text-center">
                    <p class="text-xs font-medium text-stone">Published</p>
                    <p class="text-2xl font-bold text-correct mt-1">{{ $sourcePackage->questions->where('status', 'published')->count() }}</p>
                </div>
                <div class="bg-surface border border-line rounded-xl p-4 text-center">
                    <p class="text-xs font-medium text-stone">Source authority</p>
                    <p class="text-sm font-semibold text-ink mt-2 truncate">{{ $sourcePackage->source_name ?? 'N/A' }}</p>
                </div>
                <div class="bg-surface border border-line rounded-xl p-4 text-center">
                    <p class="text-xs font-medium text-stone">Exam date</p>
                    <p class="text-sm font-semibold text-ink mt-2 truncate">{{ $sourcePackage->source_date ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Import Questions Card -->
            <div class="bg-surface rounded-xl border border-line p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-line pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-ink">Import exam questions</h2>
                        <p class="text-sm text-stone mt-0.5">Paste questions in JSON format to quickly import multiple questions into this source package.</p>
                    </div>
                    <button type="button" 
                            onclick="document.getElementById('json-example-modal').classList.toggle('hidden')"
                            class="text-xs font-medium text-accent hover:underline flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        View JSON format guide
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.source-packages.import-questions', $sourcePackage) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="topic_id" class="block text-sm font-medium text-ink mb-1">Target topic *</label>
                            <select id="topic_id" name="topic_id" required class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                                <option value="">Select a topic for imported questions...</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}">
                                        {{ $topic->code }} - {{ $topic->name }} ({{ $topic->domain }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-ink mb-1">Imported questions status *</label>
                            <select id="status" name="status" required class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                                <option value="published" selected>Published (Immediately available)</option>
                                <option value="draft">Draft (Requires review)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="json_data" class="block text-sm font-medium text-ink mb-1">Questions JSON payload *</label>
                        <textarea id="json_data" 
                                  name="json_data" 
                                  rows="8" 
                                  required
                                  placeholder='[&#10;  {&#10;    "source_question_number": 1,&#10;    "question_text": "Which of the following data structures operates on a Last-In, First-Out (LIFO) basis?",&#10;    "choices": {&#10;      "A": "Queue",&#10;      "B": "Stack",&#10;      "C": "Linked List",&#10;      "D": "Binary Tree"&#10;    },&#10;    "correct_answer_code": "B",&#10;    "explanation": "A stack is a linear data structure that follows the LIFO principle.",&#10;    "difficulty": "easy"&#10;  }&#10;]'
                                  class="w-full text-sm font-mono border-line rounded-lg focus:ring-accent focus:border-accent text-ink bg-paper/20"></textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90 transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import questions into package
                        </button>
                    </div>
                </form>
            </div>

            <!-- Attached Questions Table -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="px-5 py-4 border-b border-line bg-paper/30 flex items-center justify-between">
                    <h3 class="text-base font-bold text-ink">Package questions ({{ $sourcePackage->questions->count() }})</h3>
                    <a href="{{ route('admin.questions.create', ['source_package_id' => $sourcePackage->id]) }}" 
                       class="text-xs font-medium text-accent hover:underline">
                        + Add single question manually
                    </a>
                </div>

                @if($sourcePackage->questions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-paper/40 border-b border-line text-xs font-semibold text-stone uppercase tracking-wider">
                                    <th class="py-3.5 px-4 text-center">#</th>
                                    <th class="py-3.5 px-4">Topic</th>
                                    <th class="py-3.5 px-4">Question snippet</th>
                                    <th class="py-3.5 px-4 text-center">Answer</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line text-sm">
                                @foreach($sourcePackage->questions as $question)
                                    <tr class="hover:bg-paper/20 transition-colors">
                                        <td class="py-4 px-4 text-center font-mono text-stone text-xs">
                                            Q{{ $question->source_question_number ?? $loop->iteration }}
                                        </td>
                                        <td class="py-4 px-4 text-stone text-xs">
                                            <span class="font-medium text-ink">{{ $question->topic->code ?? 'N/A' }}</span>
                                            <div>{{ $question->topic->name ?? '' }}</div>
                                        </td>
                                        <td class="py-4 px-4 font-medium text-ink max-w-md">
                                            <div class="line-clamp-2 font-serif text-sm">
                                                {{ $question->question_text }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded font-mono font-bold text-xs bg-accent-light text-accent">
                                                {{ $question->correct_answer_code }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($question->status === 'published')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-correct-light text-correct">
                                                    Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-paper border border-line text-stone">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <a href="{{ route('admin.questions.edit', $question) }}" class="text-xs font-medium text-accent hover:underline">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-stone text-sm">
                        No questions uploaded to this package yet. Use the JSON importer above to add exam questions.
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- JSON Example Modal -->
    <div id="json-example-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-surface rounded-xl border border-line max-w-2xl w-full p-6 space-y-4 shadow-xl">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <h3 class="text-lg font-bold text-ink">JSON Questions Format Guide</h3>
                <button type="button" 
                        onclick="document.getElementById('json-example-modal').classList.add('hidden')"
                        class="text-stone hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <p class="text-sm text-stone">
                You can supply a JSON array containing questions with choices A, B, C, D and the correct answer code.
            </p>

            <pre class="bg-paper p-4 rounded-lg font-mono text-xs text-ink overflow-x-auto border border-line">
[
  {
    "source_question_number": 1,
    "question_text": "Which of the following process states means a process is waiting for an I/O operation?",
    "choices": {
      "A": "Running",
      "B": "Ready",
      "C": "Blocked / Waiting",
      "D": "Terminated"
    },
    "correct_answer_code": "C",
    "explanation": "A process enters the Blocked or Waiting state when it cannot continue until an event occurs, such as an I/O completion.",
    "difficulty": "medium"
  }
]
            </pre>

            <div class="flex justify-end pt-2">
                <button type="button" 
                        onclick="document.getElementById('json-example-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90">
                    Got it
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
