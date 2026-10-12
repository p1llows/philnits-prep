<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Edit topic') }}: {{ $topic->name }}
            </h2>
            <a href="{{ route('admin.topics.index') }}" class="text-sm text-stone hover:text-ink">
                ← Back to topics
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                    <span>ADMINISTRATION</span>
                    <span class="text-stone">·</span>
                    <span class="font-bold">EDIT TOPIC</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Edit {{ $topic->name }}</h1>
                <p class="text-sm text-stone">Update syllabus parameters, code, and visibility.</p>
            </div>

            <div class="bg-surface rounded-2xl p-6 md:p-8 border border-[#CFCABD] shadow-xs">
                
                <form action="{{ route('admin.topics.update', $topic) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Topic name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $topic->name) }}" required
                               class="block w-full rounded-xl border-[#D9D5C9] shadow-xs focus:border-accent focus:ring-accent text-sm">
                        @error('name') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Topic code (e.g. PH-01)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $topic->code) }}"
                               class="block w-full rounded-xl border-[#D9D5C9] shadow-xs focus:border-accent focus:ring-accent font-mono text-sm">
                        @error('code') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-xl border-[#D9D5C9] shadow-xs focus:border-accent focus:ring-accent text-sm">{{ old('description', $topic->description) }}</textarea>
                        @error('description') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Color hex (e.g. 1F3A5F) *</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $topic->color) }}" required maxlength="6"
                               class="block w-full rounded-xl border-[#D9D5C9] shadow-xs focus:border-accent focus:ring-accent font-mono text-sm">
                        @error('color') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $topic->is_active) ? 'checked' : '' }}
                               class="rounded border-[#D9D5C9] text-accent shadow-xs focus:ring-accent">
                        <label for="is_active" class="ml-2 block text-sm text-ink font-medium">Active (visible to learners in practice & assessments)</label>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-[#E3DFD5]">
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this topic?')) document.getElementById('delete-topic-form').submit();"
                                class="text-wrong hover:underline text-xs font-mono font-semibold">
                            Delete topic
                        </button>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.topics.index') }}" class="px-5 py-2.5 border border-[#D9D5C9] text-stone hover:text-ink hover:bg-paper rounded-xl text-xs font-medium transition-colors shadow-xs">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-accent text-surface rounded-xl hover:bg-accent/90 text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                Update topic
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-topic-form" action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
