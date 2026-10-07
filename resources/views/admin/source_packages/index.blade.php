<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-2 text-sm text-stone mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-ink transition-colors">Admin</a>
                        <span>/</span>
                        <span class="text-ink font-medium">Source packages</span>
                    </div>
                    <h1 class="text-2xl font-bold text-ink">Source packages</h1>
                    <p class="text-sm text-stone mt-1">Manage official exam packages and batch import question sets.</p>
                </div>
                <div>
                    <a href="{{ route('admin.source-packages.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add source package
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-correct-light/60 border border-correct/30 text-correct text-sm rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter & Search toolbar -->
            <div class="bg-surface rounded-xl border border-line p-4">
                <form method="GET" action="{{ route('admin.source-packages.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search package name or source..." 
                               class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink placeholder-stone/60">
                    </div>
                    <div class="w-full md:w-48">
                        <select name="status" class="w-full text-sm border-line rounded-lg focus:ring-accent focus:border-accent text-ink">
                            <option value="">All statuses</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="imported" {{ request('status') === 'imported' ? 'selected' : '' }}>Imported</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-paper hover:bg-line/40 text-ink text-sm font-medium rounded-lg transition-colors border border-line">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.source-packages.index') }}" class="px-4 py-2 bg-transparent text-stone hover:text-ink text-sm font-medium rounded-lg transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Packages Table -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                @if($sourcePackages->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-paper/40 border-b border-line text-xs font-semibold text-stone uppercase tracking-wider">
                                    <th class="py-3.5 px-4">Package title</th>
                                    <th class="py-3.5 px-4">Source & Date</th>
                                    <th class="py-3.5 px-4 text-center">Questions</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line text-sm">
                                @foreach($sourcePackages as $package)
                                    <tr class="hover:bg-paper/20 transition-colors">
                                        <td class="py-4 px-4 font-medium text-ink">
                                            <a href="{{ route('admin.source-packages.show', $package) }}" class="hover:text-accent font-semibold">
                                                {{ $package->name }}
                                            </a>
                                            @if($package->description)
                                                <p class="text-xs text-stone line-clamp-1 mt-0.5 font-normal">{{ $package->description }}</p>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-stone">
                                            <div>{{ $package->source_name ?? 'N/A' }}</div>
                                            @if($package->source_date)
                                                <div class="text-xs text-stone/70">{{ $package->source_date }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-light text-accent">
                                                {{ $package->questions_count }} questions
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($package->status === 'published')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-correct-light text-correct">
                                                    Published
                                                </span>
                                            @elseif($package->status === 'draft')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-paper border border-line text-stone">
                                                    Draft
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-accent-light text-accent">
                                                    {{ ucfirst($package->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right space-x-2">
                                            <a href="{{ route('admin.source-packages.show', $package) }}" class="text-xs font-medium text-accent hover:underline">
                                                View & Import
                                            </a>
                                            <a href="{{ route('admin.source-packages.edit', $package) }}" class="text-xs font-medium text-stone hover:text-ink">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($sourcePackages->hasPages())
                        <div class="p-4 border-t border-line bg-paper/20">
                            {{ $sourcePackages->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-stone/40 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-base font-semibold text-ink">No source packages found</h3>
                        <p class="text-sm text-stone mt-1 max-w-md mx-auto">
                            Start by creating a source package to represent official exam sets (e.g. PhilNITS FE Morning Exam 2023).
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.source-packages.create') }}" class="inline-flex items-center px-4 py-2 bg-accent text-white font-medium text-sm rounded-lg hover:bg-accent/90 transition-colors">
                                Add source package
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
