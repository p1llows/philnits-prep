<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ADMINISTRATION</span>
                        <span class="text-stone">·</span>
                        <span class="font-bold">SYLLABUS</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Topic management</h1>
                    <p class="text-sm text-stone">Manage all examination topics, descriptions, status, and question mappings.</p>
                </div>
                <a href="{{ route('admin.topics.create') }}" 
                   class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Create new topic</span>
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-correct-surface/50 border border-correct/30 text-correct text-xs font-mono font-medium rounded-xl flex items-center shadow-xs">
                    <span class="mr-2 font-bold">✓</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-wrong-surface/50 border border-wrong/30 text-wrong text-xs font-mono font-medium rounded-xl flex items-center shadow-xs">
                    <span class="mr-2 font-bold">✕</span> {{ session('error') }}
                </div>
            @endif

            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E3DFD5]">
                        <thead class="bg-panel">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Code</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Questions</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-mono font-medium text-stone uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-[#E3DFD5]">
                            @forelse($topics as $topic)
                                <tr class="hover:bg-paper/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-accent">
                                        {{ $topic->code ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-ink">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-accent"></span>
                                            <span class="font-medium text-sm">{{ $topic->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-stone">
                                        {{ $topic->questions_count }} questions
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.topics.toggle-status', $topic) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs font-mono font-semibold rounded-full border {{ $topic->is_active ? 'bg-correct-surface/50 text-correct border-correct/30' : 'bg-paper text-stone border-[#D9D5C9]' }} transition-colors shadow-xs">
                                                {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                        <a href="{{ route('admin.topics.show', $topic) }}" class="inline-flex items-center px-3 py-1 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-lg text-xs font-medium transition-all shadow-xs">View</a>
                                        <a href="{{ route('admin.topics.edit', $topic) }}" class="inline-flex items-center px-3 py-1 bg-accent text-surface hover:bg-accent/90 rounded-lg text-xs font-semibold shadow-xs">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-xs text-stone">
                                        No topics created yet. Click "+ Create new topic" to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($topics->hasPages())
                    <div class="px-6 py-4 border-t border-[#E3DFD5] bg-panel">
                        {{ $topics->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

