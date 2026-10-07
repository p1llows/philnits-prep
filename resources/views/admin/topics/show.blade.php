<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Topic Detail') }}: {{ $topic->name }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('admin.topics.edit', $topic) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    Edit Topic
                </a>
                <a href="{{ route('admin.topics.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Topics
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Topic Meta Details -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="font-mono text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-800">
                                {{ $topic->code ?? 'No Code' }}
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $topic->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $topic->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $topic->name }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $topic->description ?? 'No description.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Published Questions Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-900">Associated Questions</h4>
                    <span class="text-xs text-gray-500">{{ $topic->questions->count() }} published questions</span>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($topic->questions as $question)
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <span class="font-bold text-sm text-blue-600">Question #{{ $question->source_question_number ?? $question->id }}</span>
                                <span class="text-xs text-gray-400">Status: {{ ucfirst($question->status) }}</span>
                            </div>
                            <p class="text-gray-900 font-medium mb-3">{{ $question->question_text }}</p>
                            
                            @if($question->explanation)
                                <div class="p-3 bg-gray-50 rounded text-xs text-gray-600">
                                    <strong>Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            No published questions assigned to this topic yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
