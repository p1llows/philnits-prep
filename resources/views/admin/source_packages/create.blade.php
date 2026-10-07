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
                    <span class="text-ink font-medium">New package</span>
                </div>
                <h1 class="text-2xl font-bold text-ink">Create source package</h1>
                <p class="text-sm text-stone mt-1">Define package metadata for organizing official examination question sets.</p>
            </div>

            <!-- Create Form Card -->
            <div class="bg-surface rounded-xl border border-line p-6">
                <form method="POST" action="{{ route('admin.source-packages.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-ink mb-1">Package name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g. PhilNITS FE Morning Exam 2023 Spring"
                               class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink placeholder-stone/50">
                        @error('name')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-ink mb-1">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Overview of examination scope, format, and target audience..."
                                  class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink placeholder-stone/50">{{ old('description') }}</textarea>
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
                                   value="{{ old('source_name', 'IPA Japan / PhilNITS Foundation') }}" 
                                   placeholder="e.g. PhilNITS Foundation"
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
                                   value="{{ old('source_date') }}" 
                                   placeholder="e.g. April 2023"
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
                               value="{{ old('attribution_note', 'Official past exam questions released for public preparation.') }}" 
                               class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                        @error('attribution_note')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-ink mb-1">Initial status *</label>
                        <select id="status" name="status" class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="imported" {{ old('status') === 'imported' ? 'selected' : '' }}>Imported</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-wrong mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-line flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.source-packages.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-stone hover:text-ink transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90 transition-colors shadow-sm">
                            Create package
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
