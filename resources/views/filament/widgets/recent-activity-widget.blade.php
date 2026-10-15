<x-filament-widgets::widget>
    <div class="bg-white rounded-xl border border-[#E3DFD5] p-6 shadow-none h-full flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-[#6E6A60]">Recent activity</h3>
                <a href="{{ route('filament.admin.resources.audit-logs.index') }}" class="text-xs font-normal text-[#A29D91] hover:text-[#1F3A5F]">
                    View all &rarr;
                </a>
            </div>

            @if($activities->isEmpty())
                <div class="py-8 text-center text-xs text-[#A29D91]">
                    No recent activity recorded yet.
                </div>
            @else
                <div class="divide-y divide-[#E3DFD5]/60">
                    @foreach($activities as $activity)
                        <div class="py-3 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                            <div class="text-xs font-medium text-[#1B1A17] truncate">
                                @php
                                    $userName = $activity->user?->name ?? 'System';
                                    $actionLabel = match($activity->action) {
                                        'question.approved' => 'approved question',
                                        'question.rejected' => 'marked question as needs fix',
                                        'question.edited' => 'edited question',
                                        'package.uploaded' => 'uploaded package',
                                        'package.published' => 'published questions from package',
                                        'role.changed' => 'changed user role',
                                        'user.deactivated' => 'deactivated user account',
                                        'user.reactivated' => 'reactivated user account',
                                        'pretest.reset' => 'reset pre-test',
                                        'rules.updated' => 'updated pre-test rules',
                                        'settings.updated' => 'updated app settings',
                                        default => str_replace(['.', '_'], ' ', $activity->action),
                                    };
                                    $targetStr = $activity->target_id ? " #{$activity->target_id}" : '';
                                @endphp
                                <span>{{ $userName }} {{ $actionLabel }}{{ $targetStr }}</span>
                            </div>
                            <span class="text-[11px] text-[#A29D91] shrink-0 whitespace-nowrap">
                                {{ $activity->created_at?->diffForHumans(short: true) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
