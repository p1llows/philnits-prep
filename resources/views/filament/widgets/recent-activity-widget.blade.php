<x-filament-widgets::widget>
    <div class="rounded-xl border border-[#E3DFD5] bg-white p-5 shadow-2xs h-full flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#1F3A5F]">Recent activity</h3>
                    <p class="text-xs text-[#6E6A60] mt-0.5">Admin & system audit log</p>
                </div>
                <a href="{{ route('filament.admin.resources.audit-logs.index') }}" class="text-xs font-semibold text-[#1F3A5F] hover:underline">
                    View full log &rarr;
                </a>
            </div>

            @if($activities->isEmpty())
                <div class="py-10 text-center flex flex-col items-center justify-center">
                    <div class="w-10 h-10 rounded-full bg-[#FAF9F5] border border-[#EFECE4] flex items-center justify-center text-[#A29D91] mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-[#6E6A60]">No admin actions recorded yet</p>
                    <p class="text-[11px] text-[#A29D91] mt-0.5">Events will appear here as questions and packages are managed</p>
                </div>
            @else
                <div class="divide-y divide-[#EFECE4]">
                    @foreach($activities as $activity)
                        <div class="py-2.5 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                            <div class="text-xs font-medium text-[#1B1A17] truncate flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#3A5F92] shrink-0"></span>
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
                                <span class="truncate"><strong>{{ $userName }}</strong> {{ $actionLabel }}{{ $targetStr }}</span>
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
