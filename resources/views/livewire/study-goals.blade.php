<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Study Goals</h3>
    </div>
    @if(session()->has('success'))
        <div class="p-2 mb-4 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="space-y-4">
        @foreach($myGoals as $goal)
            <div class="p-4 bg-gray-50 border rounded-lg">
                <div class="font-medium">{{ $goal['title'] }}</div>
                <div class="text-sm text-gray-500">{{ $goal['type_display'] }}</div>
            </div>
        @endforeach
    </div>
</div>
