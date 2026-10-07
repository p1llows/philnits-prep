<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Questions Management') }}
            </h2>
            <a href="{{ route('admin.questions.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 text-sm shadow-sm transition-colors">
                + Add New Question
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters Bar -->
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
                <form action="{{ route('admin.questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="topic_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Topic</label>
                        <select name="topic_id" id="topic_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Topics</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }} ({{ $topic->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validated</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Search</label>
                        <input type="text" name="search" id="search" placeholder="Search question text..." value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Questions Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Question Bank</h3>
                    <span class="text-xs text-gray-500">Total: {{ $questions->total() }} questions</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Topic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question Text</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ans</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($questions as $question)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-xs text-gray-500">#{{ $question->source_question_number ?? $question->id }}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                            {{ $question->topic->code ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-md">
                                        <p class="line-clamp-2 font-medium">{{ $question->question_text }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="w-7 h-7 rounded-md bg-green-600 text-white font-bold flex items-center justify-center text-xs">
                                            {{ $question->correct_answer_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.questions.publish', $question) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $question->status === 'published' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                                                {{ ucfirst($question->status) }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.questions.show', $question) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        No questions found. Click "+ Add New Question" to create your first question.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($questions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
