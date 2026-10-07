<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Add Question') }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Questions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 border border-gray-200">
                
                <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Meta Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="topic_id" class="block text-sm font-medium text-gray-700">Topic *</label>
                            <select name="topic_id" id="topic_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Topic</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }} ({{ $topic->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty *</label>
                            <select name="difficulty" id="difficulty" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Active for users)</option>
                                <option value="validated" {{ old('status') == 'validated' ? 'selected' : '' }}>Validated</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                        <textarea name="question_text" id="question_text" rows="4" required
                                  placeholder="Type the examination question text here..."
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Choices (A, B, C, D) -->
                    <div class="space-y-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-900">Choices & Correct Answer *</label>
                        
                        @foreach(['A', 'B', 'C', 'D'] as $code)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <input type="radio" name="correct_answer_code" value="{{ $code }}" id="correct_{{ $code }}"
                                       {{ old('correct_answer_code', 'A') === $code ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <label for="correct_{{ $code }}" class="font-bold text-gray-700 w-6 text-center">{{ $code }}.</label>
                                <input type="text" name="choices[{{ $code }}]" value="{{ old('choices.' . $code) }}" required
                                       placeholder="Text for option {{ $code }}..."
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        @endforeach
                        <p class="text-xs text-gray-500">Select the radio button next to the correct choice.</p>
                        @error('correct_answer_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Explanation -->
                    <div class="pt-4 border-t border-gray-200">
                        <label for="explanation" class="block text-sm font-medium text-gray-700">Explanation / Answer Rationale</label>
                        <textarea name="explanation" id="explanation" rows="3"
                                  placeholder="Provide clear explanation for why the answer is correct..."
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('explanation') }}</textarea>
                        @error('explanation') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            Save Question
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
