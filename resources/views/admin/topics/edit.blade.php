<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Edit Topic') }}: {{ $topic->name }}
            </h2>
            <a href="{{ route('admin.topics.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Topics
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 border border-gray-200">
                
                <form action="{{ route('admin.topics.update', $topic) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Topic Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $topic->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Topic Code (e.g. PH-01)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $topic->code) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono">
                        @error('code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $topic->description) }}</textarea>
                        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Color Hex (e.g. 3B82F6) *</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $topic->color) }}" required maxlength="6"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono">
                        @error('color') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $topic->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active (Visible to users)</label>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this topic?')) document.getElementById('delete-topic-form').submit();"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Delete Topic
                        </button>
                        <div class="space-x-4">
                            <a href="{{ route('admin.topics.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                Update Topic
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
