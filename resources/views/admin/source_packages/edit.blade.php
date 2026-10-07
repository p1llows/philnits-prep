<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Breadcrumbs & Title -->
            <div>
                <div class="flex items-center space-x-2 text-sm text-stone mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-ink transition-colors">Admin</a>
                    <span>/</span>
                    <a href="{{ route('admin.source-packages.index') }}" class="hover:text-ink transition-colors">Source packages</a>
                    <span>/</span>
                    <span class="text-ink font-medium">Edit package</span>
                </div>
                <h1 class="text-2xl font-bold text-ink">Edit source package</h1>
                <p class="text-sm text-stone mt-1">Update details for {{ $sourcePackage->name }}.</p>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-surface rounded-xl border border-line p-6">
                <form method="POST" action="{{ route('admin.source-packages.update', $sourcePackage) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-ink mb-1">Package name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $sourcePackage->name) }}" 
                               required 
                               class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                        @error('name')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-ink mb-1">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">{{ old('description', $sourcePackage->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="source_name" class="block text-sm font-medium text-ink mb-1">Source organization / authority</label>
                            <input type="text" 
                                   id="source_name" 
                                   name="source_name" 
                                   value="{{ old('source_name', $sourcePackage->source_name) }}" 
                                   class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                            @error('source_name')
                                <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="source_date" class="block text-sm font-medium text-ink mb-1">Exam date / season</label>
                            <input type="text" 
                                   id="source_date" 
                                   name="source_date" 
                                   value="{{ old('source_date', $sourcePackage->source_date) }}" 
                                   class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                            @error('source_date')
                                <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="attribution_note" class="block text-sm font-medium text-ink mb-1">Attribution / copyright note</label>
                        <input type="text" 
                               id="attribution_note" 
                               name="attribution_note" 
                               value="{{ old('attribution_note', $sourcePackage->attribution_note) }}" 
                               class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                        @error('attribution_note')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-ink mb-1">Status *</label>
                        <select id="status" name="status" class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                            <option value="draft" {{ old('status', $sourcePackage->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="imported" {{ old('status', $sourcePackage->status) === 'imported' ? 'selected' : '' }}>Imported</option>
                            <option value="published" {{ old('status', $sourcePackage->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $sourcePackage->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-line flex items-center justify-between">
                        <form method="POST" action="{{ route('admin.source-packages.destroy', $sourcePackage) }}" onsubmit="return confirm('Are you sure you want to delete this source package? Attached questions will be unlinked.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-wrong hover:underline">
                                Delete package
                            </button>
                        </form>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.source-packages.show', $sourcePackage) }}" 
                               class="px-4 py-2 text-sm font-medium text-stone hover:text-ink transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90 transition-colors shadow-sm">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
