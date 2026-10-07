<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assessment History') }}
            </h2>
            <a href="{{ route('assessment.index') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-medium">
                + Take New Assessment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Past Assessment Performance</h3>
                    <p class="text-sm text-gray-600 mt-1">Review your historical attempts, scores, and completion details.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Taken</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Spent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assessments as $assessment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $assessment->started_at?->format('M j, Y g:i A') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $assessment->assessment_type ?? 'General')) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ floor(($assessment->time_spent_seconds ?? 0) / 60) }}m {{ ($assessment->time_spent_seconds ?? 0) % 60 }}s
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assessment->score_percentage !== null)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $assessment->score_percentage >= 60 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ number_format($assessment->score_percentage, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">In Progress</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($assessment->status === 'in_progress')
                                            <a href="{{ route('assessment.show', $assessment) }}" class="text-primary-600 hover:text-primary-900">Continue →</a>
                                        @else
                                            <a href="{{ route('assessment.results', $assessment) }}" class="text-blue-600 hover:text-blue-900">View Results</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No assessment history yet. <a href="{{ route('assessment.index') }}" class="text-primary-600 font-semibold underline">Take your first assessment</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assessments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
