<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Question Preview') }} #{{ $question->id }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('admin.questions.edit', $question) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    Edit Question
                </a>
                <a href="{{ route('admin.questions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Questions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Question Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 md:p-8 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $question->topic->name ?? 'General' }} ({{ $question->topic->code ?? 'N/A' }})
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $question->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($question->status) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">
                            Difficulty: {{ ucfirst($question->difficulty ?? 'Medium') }}
                        </span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 leading-relaxed">
                        {{ $question->question_text }}
                    </h3>
                </div>

                <div class="p-6 md:p-8 space-y-3">
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Options</h4>
                    
                    @foreach($question->choices as $choice)
                        @php
                            $isCorrect = ($choice->code === $question->correct_answer_code);
                        @endphp
                        <div class="p-4 rounded-lg border-2 flex items-center {{ $isCorrect ? 'bg-green-50 border-green-300 text-green-900' : 'bg-white border-gray-200 text-gray-700' }}">
                            <div class="w-8 h-8 rounded-md flex items-center justify-center font-bold mr-3 {{ $isCorrect ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                                {{ $choice->code }}
                            </div>
                            <div class="flex-grow font-medium text-sm">
                                {{ $choice->choice_text ?? $choice->text }}
                            </div>
                            @if($isCorrect)
                                <span class="text-xs font-bold text-green-700 bg-green-200 px-2 py-0.5 rounded">
                                    Correct Answer ✓
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($question->explanation)
                    <div class="p-6 bg-yellow-50 border-t border-yellow-200">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Explanation</h4>
                        <p class="text-sm text-gray-800 leading-relaxed">{{ $question->explanation }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
