<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-ink mb-1">Topic management</h1>
                    <p class="text-sm text-stone">Manage all examination topics, descriptions, status, and question mappings.</p>
                </div>
                <a href="{{ route('admin.topics.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors shrink-0">
                    + Create new topic
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-correct-surface border border-line text-correct text-xs font-medium rounded-lg flex items-center">
                    <span class="mr-2">✓</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-wrong-surface border border-line text-wrong text-xs font-medium rounded-lg flex items-center">
                    <span class="mr-2">✕</span> {{ session('error') }}
                </div>
            @endif

            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line">
                        <thead class="bg-paper/50">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Code</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Name</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Questions</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Status</th>
                                <th class="px-5 py-3.5 text-right text-xs font-medium text-stone">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-line">
                            @forelse($topics as $topic)
                                <tr class="hover:bg-paper/30 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap font-mono text-xs font-medium text-ink">
                                        {{ $topic->code ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-ink">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-accent"></span>
                                            <span class="font-medium">{{ $topic->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-stone">
                                        {{ $topic->questions_count }} questions
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.topics.toggle-status', $topic) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-0.5 text-xs font-medium rounded border border-line {{ $topic->is_active ? 'bg-correct-surface text-correct' : 'bg-paper text-stone' }}">
                                                {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-xs font-medium space-x-3">
                                        <a href="{{ route('admin.topics.show', $topic) }}" class="text-accent hover:underline">View</a>
                                        <a href="{{ route('admin.topics.edit', $topic) }}" class="text-accent hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-xs text-stone">
                                        No topics created yet. Click "+ Create new topic" to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($topics->hasPages())
                    <div class="px-5 py-4 border-t border-line bg-paper/30">
                        {{ $topics->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

