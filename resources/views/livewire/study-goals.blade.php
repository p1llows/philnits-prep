<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-ink">Study goals</h3>
    </div>
    @if(session()->has('success'))
        <div class="p-3 mb-4 text-correct bg-correct-surface rounded-lg border border-correct/30 text-sm">
            {{ session('success') }}
        </div>
    @endif
    <div class="space-y-4">
        @foreach($myGoals as $goal)
            <div class="p-4 bg-paper border border-line rounded-lg">
                <div class="font-medium text-ink">{{ $goal['title'] }}</div>
                <div class="text-sm text-stone">{{ $goal['type_display'] }}</div>
            </div>
        @endforeach
    </div>
</div>
