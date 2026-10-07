<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $topic->name }} ({{ $topic->code }})
            </h2>
            <a href="{{ route('topics.practice', $topic) }}" 
               class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm shadow-sm">
                Start Practice Mode →
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Topic Header Banner -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4" style="border-color: #{{ $topic->color ?? '3B82F6' }}">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $topic->name }}</h3>
                <p class="text-gray-600 leading-relaxed mb-4">{{ $topic->description ?? 'No description provided.' }}</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $questions->count() }} Questions Available
                    </span>
                    @if(count($mistakeIds) > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ count($mistakeIds) }} Unresolved Mistakes
                        </span>
                    @endif
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-900">Topic Questions</h4>
                    <span class="text-xs text-gray-500">Ordered by question number</span>
                </div>

                @if($questions->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($questions as $index => $question)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold text-sm">
                                            #{{ $question->source_question_number ?? ($index + 1) }}
                                        </span>
                                        @if(in_array($question->id, $mistakeIds->toArray()))
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                Needs Review
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400">
                                        Difficulty: {{ ucfirst($question->difficulty ?? 'Medium') }}
                                    </span>
                                </div>

                                <p class="text-gray-900 font-medium text-base mb-4 leading-relaxed">
                                    {{ $question->question_text }}
                                </p>

                                <!-- Options List -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                                    @foreach($question->choices as $choice)
                                        <div class="p-3 rounded-lg border text-sm flex items-center {{ $choice->code === $question->correct_answer_code ? 'bg-green-50 border-green-200 text-green-900' : 'bg-gray-50 border-gray-200 text-gray-700' }}">
                                            <span class="font-bold mr-2 w-6 h-6 rounded flex items-center justify-center text-xs {{ $choice->code === $question->correct_answer_code ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                                {{ $choice->code }}
                                            </span>
                                            <span>{{ $choice->choice_text }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($question->explanation)
                                    <div class="p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg text-xs text-gray-700">
                                        <span class="font-semibold text-gray-900">Explanation:</span> {{ $question->explanation }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No published questions found for this topic yet.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
