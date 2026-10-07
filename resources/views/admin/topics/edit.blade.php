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

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface rounded-xl p-6 md:p-8 border border-line">
                
                <form action="{{ route('admin.topics.update', $topic) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-stone">Topic name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $topic->name) }}" required
                               class="mt-1 block w-full rounded-lg border-line shadow-sm focus:border-accent focus:ring-accent">
                        @error('name') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-stone">Topic code (e.g. PH-01)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $topic->code) }}"
                               class="mt-1 block w-full rounded-lg border-line shadow-sm focus:border-accent focus:ring-accent font-mono">
                        @error('code') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-stone">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-lg border-line shadow-sm focus:border-accent focus:ring-accent">{{ old('description', $topic->description) }}</textarea>
                        @error('description') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-stone">Color hex (e.g. 1F3A5F) *</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $topic->color) }}" required maxlength="6"
                               class="mt-1 block w-full rounded-lg border-line shadow-sm focus:border-accent focus:ring-accent font-mono">
                        @error('color') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $topic->is_active) ? 'checked' : '' }}
                               class="rounded border-line text-accent shadow-sm focus:border-accent focus:ring-accent">
                        <label for="is_active" class="ml-2 block text-sm text-ink">Active (visible to users)</label>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-line">
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this topic?')) document.getElementById('delete-topic-form').submit();"
                                class="text-wrong hover:opacity-80 text-sm font-medium">
                            Delete topic
                        </button>
                        <div class="space-x-4">
                            <a href="{{ route('admin.topics.index') }}" class="px-4 py-2 border border-line text-stone hover:text-ink hover:bg-paper rounded-lg text-sm font-medium">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
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
