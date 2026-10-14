<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ATTEMPT LOGS</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Assessment history</h1>
                    <p class="text-sm text-stone">Review your historical attempts, scores, and completion details.</p>
                </div>
                <a href="{{ route('assessment.index') }}" class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Take new assessment</span>
                </a>
            </div>

            <!-- History Table Card -->
            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E3DFD5]">
                        <thead class="bg-panel">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Date taken</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Time spent</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Score</th>
                                <th class="px-6 py-4 text-left text-xs font-mono font-medium text-stone uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-mono font-medium text-stone uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-[#E3DFD5]">
                            @forelse($assessments as $assessment)
                                <tr class="hover:bg-paper/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-ink">
                                        {{ $assessment->started_at?->format('M j, Y g:i A') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-stone">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono bg-paper border border-[#D9D5C9] text-stone">
                                            {{ ucfirst(str_replace('_', ' ', $assessment->assessment_type ?? 'General')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-stone">
                                        {{ floor(($assessment->time_spent_seconds ?? 0) / 60) }}m {{ ($assessment->time_spent_seconds ?? 0) % 60 }}s
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assessment->score_percentage !== null)
                                            <span class="px-3 py-1 inline-flex text-xs font-mono font-bold rounded-full border {{ $assessment->score_percentage >= 60 ? 'bg-correct-surface/50 text-correct border-correct/30' : 'bg-wrong-surface/50 text-wrong border-wrong/30' }}">
                                                {{ number_format($assessment->score_percentage, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-xs font-mono text-stone">In progress</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-mono {{ $assessment->status === 'completed' ? 'text-correct bg-correct-surface/40' : 'text-stone bg-paper' }}">
                                            {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        @if($assessment->status === 'in_progress')
                                            <a href="{{ route('assessment.show', $assessment) }}" class="inline-flex items-center space-x-1 px-3 py-1 bg-accent text-surface hover:bg-accent/90 rounded-lg text-xs font-semibold shadow-xs">
                                                <span>Continue</span>
                                                <span>→</span>
                                            </a>
                                        @else
                                            <a href="{{ route('assessment.results', $assessment) }}" class="inline-flex items-center space-x-1 px-3 py-1 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-lg text-xs font-medium transition-all shadow-xs">
                                                <span>View results</span>
                                                <svg class="w-3.5 h-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-xs text-stone">
                                        <div class="mb-4 flex justify-center">
                                            <x-mascot variant="inspect" :size="110" class="w-[80px] h-[80px] sm:w-[110px] sm:h-[110px] drop-shadow-sm" />
                                        </div>
                                        No assessment history yet. <a href="{{ route('assessment.index') }}" class="text-accent font-semibold hover:underline">Take your first assessment</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assessments->hasPages())
                    <div class="px-6 py-4 border-t border-[#E3DFD5] bg-panel">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

