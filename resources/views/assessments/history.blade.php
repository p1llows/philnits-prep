<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-ink mb-1">Assessment history</h1>
                    <p class="text-sm text-stone">Review your historical attempts, scores, and completion details.</p>
                </div>
                <a href="{{ route('assessment.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors shrink-0">
                    + Take new assessment
                </a>
            </div>

            <!-- History Table Card -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line">
                        <thead class="bg-paper/50">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Date taken</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Type</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Time spent</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Score</th>
                                <th class="px-5 py-3.5 text-left text-xs font-medium text-stone">Status</th>
                                <th class="px-5 py-3.5 text-right text-xs font-medium text-stone">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-line">
                            @forelse($assessments as $assessment)
                                <tr class="hover:bg-paper/30 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-ink">
                                        {{ $assessment->started_at?->format('M j, Y g:i A') ?? 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-stone">
                                        {{ ucfirst(str_replace('_', ' ', $assessment->assessment_type ?? 'General')) }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-stone">
                                        {{ floor(($assessment->time_spent_seconds ?? 0) / 60) }}m {{ ($assessment->time_spent_seconds ?? 0) % 60 }}s
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($assessment->score_percentage !== null)
                                            <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded border border-line {{ $assessment->score_percentage >= 60 ? 'bg-correct-surface text-correct' : 'bg-wrong-surface text-wrong' }}">
                                                {{ number_format($assessment->score_percentage, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-xs text-stone">In progress</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-stone">
                                        {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        @if($assessment->status === 'in_progress')
                                            <a href="{{ route('assessment.show', $assessment) }}" class="text-accent hover:underline">Continue →</a>
                                        @else
                                            <a href="{{ route('assessment.results', $assessment) }}" class="text-accent hover:underline">View results</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-xs text-stone">
                                        No assessment history yet. <a href="{{ route('assessment.index') }}" class="text-accent font-medium hover:underline">Take your first assessment</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assessments->hasPages())
                    <div class="px-5 py-4 border-t border-line bg-paper/30">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

