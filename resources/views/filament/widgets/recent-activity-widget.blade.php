<div class="bg-white rounded-xl border border-[#E3DFD5] p-6 shadow-none">
    <div class="flex items-center justify-between pb-4 border-b border-[#E3DFD5] mb-4">
        <div>
            <h3 class="text-base font-semibold text-[#1F3A5F]">Recent activity</h3>
            <p class="text-xs text-[#6E6A60]">Latest administrative and system actions</p>
        </div>
        <a href="{{ route('filament.admin.resources.audit-logs.index') }}" class="text-xs font-medium text-[#1F3A5F] hover:underline">
            View full log &rarr;
        </a>
    </div>

    @if($activities->isEmpty())
        <div class="py-8 text-center text-sm text-[#6E6A60]">
            No recent activity recorded yet.
        </div>
    @else
        <div class="divide-y divide-[#E3DFD5]/60">
            @foreach($activities as $activity)
                <div class="py-3 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        @php
                            $badgeColor = match(true) {
                                str_contains($activity->action, 'rejected') || str_contains($activity->action, 'deactivated') => 'bg-[#A3392F]/10 text-[#A3392F] border-[#A3392F]/20',
                                str_contains($activity->action, 'approved') || str_contains($activity->action, 'published') || str_contains($activity->action, 'reactivated') => 'bg-[#1F3A5F]/10 text-[#1F3A5F] border-[#1F3A5F]/20',
                                default => 'bg-[#E3DFD5]/50 text-[#57534A] border-[#E3DFD5]',
                            };
                            $targetName = empty($activity->target_type) ? '—' : (class_basename($activity->target_type) . ($activity->target_id ? ' #' . $activity->target_id : ''));
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $badgeColor }}">
                            {{ ucwords(str_replace(['.', '_'], ' ', $activity->action)) }}
                        </span>
                        <div>
                            <span class="text-xs font-medium text-[#1F3A5F]">{{ $targetName }}</span>
                            <span class="text-xs text-[#6E6A60]">by {{ $activity->user?->name ?? 'System' }}</span>
                        </div>
                    </div>
                    <span class="text-xs text-[#A29D91] whitespace-nowrap">
                        {{ $activity->created_at?->diffForHumans() }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
