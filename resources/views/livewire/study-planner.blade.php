<div class="space-y-6">
    
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-ink mb-2">Study planner</h1>
            <p class="text-stone">Plan your exam preparation with personalized schedules and goals.</p>
        </div>
        
        <button 
            wire:click="openCreateModal"
            class="px-6 py-3 bg-ink text-surface rounded-lg hover:bg-stone transition-colors shadow-sm flex items-center space-x-2 text-sm font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Create schedule</span>
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <div class="bg-surface rounded-xl p-4 border border-line">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-stone">Active schedules</span>
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-ink">{{ count($mySchedules) }}</p>
            <p class="text-xs text-stone mt-1">Currently planned</p>
        </div>

        <div class="bg-surface rounded-xl p-4 border border-line">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-stone">This week</span>
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-ink">
                {{ collect($mySchedules)->filter(fn($s) => $s['is_committed'])->sum('weekly_hours') }} hrs
            </p>
            <p class="text-xs text-stone mt-1">Committed study time</p>
        </div>

        <div class="bg-surface rounded-xl p-4 border border-line">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-stone">Completed</span>
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-ink">
                {{ collect($mySchedules)->sum('completed_sessions') }}
            </p>
            <p class="text-xs text-stone mt-1">Sessions completed</p>
        </div>

        <div class="bg-surface rounded-xl p-4 border border-line">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-stone">Next session</span>
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-lg font-bold text-ink">Today</p>
            <p class="text-xs text-accent mt-1">Check upcoming sessions</p>
        </div>
    </div>

    <!-- My Schedules Grid -->
    @if(count($mySchedules) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            @foreach($mySchedules as $schedule)
                
                <div class="bg-surface rounded-xl border border-line overflow-hidden">
                    
                    <!-- Card Header with Color Bar -->
                    <div class="h-1.5 bg-accent"></div>
                    
                    <div class="p-6">
                        <!-- Title and Actions -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-ink mb-1">{{ $schedule['title'] }}</h3>
                                
                                @if($schedule['description'])
                                    <p class="text-sm text-stone line-clamp-2">{{ $schedule['description'] }}</p>
                                @endif
                                
                                <div class="mt-2 flex items-center space-x-3 text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded {{ $schedule['is_committed'] ? 'bg-correct-surface text-correct' : 'bg-paper text-stone border border-line' }}">
                                        @if($schedule['is_committed'])
                                            ✓ Committed
                                        @else
                                            Draft
                                        @endif
                                    </span>
                                    
                                    @if($schedule['committed_at'])
                                        <span class="text-stone">Committed {{ $schedule['committed_at'] }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-1 ml-3">
                                <button 
                                    wire:click="openEditModal({{ $schedule['id'] }})"
                                    class="p-2 text-stone hover:text-ink hover:bg-paper rounded-lg transition-colors"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                
                                <button 
                                    wire:click="deleteSchedule({{ $schedule['id'] }})"
                                    class="p-2 text-stone hover:text-wrong hover:bg-wrong-surface rounded-lg transition-colors"
                                    title="Delete"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Progress Section -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-stone">Progress</span>
                                <span class="text-sm text-stone">
                                    {{ $schedule['completed_sessions'] }}/{{ $schedule['total_sessions'] }} sessions
                                </span>
                            </div>
                            
                            <div class="w-full bg-line rounded-full h-2">
                                <div 
                                    class="bg-accent h-2 rounded-full transition-all duration-300" 
                                    style="width: {{ $schedule['total_sessions'] > 0 ? ($schedule['completed_sessions'] / $schedule['total_sessions']) * 100 : 0 }}%"
                                ></div>
                            </div>
                            
                            <div class="mt-2 flex items-center justify-between text-xs text-stone">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $schedule['weekly_hours'] }} hrs/week</span>
                                </div>
                                <span class="uppercase text-stone font-medium">{{ ucfirst($schedule['intensity']) }}</span>
                            </div>
                        </div>
                        
                        <!-- Upcoming Sessions Preview -->
                        @if(count($schedule['upcoming_sessions']) > 0)
                            <div class="mb-4 bg-paper rounded-lg p-3 border border-line">
                                <p class="text-xs font-medium text-stone mb-2 uppercase tracking-wide">Up next:</p>
                                @foreach($schedule['upcoming_sessions'] as $session)
                                    <div class="text-xs text-ink flex items-start space-x-2">
                                        <svg class="w-4 h-4 text-stone mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $session['scheduled_date']->format('M j') }} - {{ $session['session_type'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                wire:click="toggleActive({{ $schedule['id'] }})"
                                class="px-3 py-2 text-sm border border-line text-stone hover:text-ink hover:bg-paper rounded-lg transition-colors font-medium">
                                {{ $schedule['is_active'] ? 'Deactivate' : 'Activate' }}
                            </button>
                            
                            @if(!$schedule['is_committed'])
                                <button 
                                    wire:click="commitToSchedule({{ $schedule['id'] }})"
                                    class="px-3 py-2 bg-ink text-surface rounded-lg hover:bg-stone transition-colors font-medium text-sm">
                                    Commit to schedule
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
            @endforeach
        </div>
        
        @if(count($mySchedules) === 0)
            <div class="text-center py-12 bg-surface rounded-xl border border-line">
                <svg class="mx-auto h-12 w-12 text-stone mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-ink">No study schedules yet</h3>
                <p class="text-stone mt-1">Create your first schedule to start planning your study time!</p>
            </div>
        @endif
    @endif

    <!-- Create/Edit Modal -->
    <div x-data="{ show: @entangle('showCreateModal') }" x-show="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-surface rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-line">
            <div class="px-6 py-4 border-b border-line">
                <h2 class="text-xl font-semibold text-ink">{{ $editingScheduleId ? 'Edit study schedule' : 'Create new study schedule' }}</h2>
            </div>
            
            <form wire:submit="saveSchedule" class="p-6 space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Schedule title *</label>
                    <input type="text" wire:model="scheduleTitle" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent" placeholder="e.g., IP Passport Prep - 3 Month Plan">
                    @error('scheduleTitle') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Description (optional)</label>
                    <textarea wire:model="scheduleDescription" rows="2" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Brief description of your study goals..."></textarea>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone mb-1">Start date *</label>
                        <input type="date" wire:model="startDate" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent">
                        @error('startDate') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone mb-1">End date (optional)</label>
                        <input type="date" wire:model="endDate" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent">
                        @error('endDate') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Weekly Hours & Intensity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone mb-1">Weekly study hours *</label>
                        <select wire:model="weeklyStudyHours" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent">
                            @for($i = 1; $i <= 40; $i++)
                                <option value="{{ $i }}">{{ $i }} hour{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        @error('weeklyStudyHours') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone mb-1">Study intensity *</label>
                        <select wire:model="intensity" class="w-full px-3 py-2 border border-line rounded-lg text-ink focus:outline-none focus:ring-2 focus:ring-accent">
                            <option value="light">Light (3-5 hrs/week)</option>
                            <option value="moderate">Moderate (5-10 hrs/week)</option>
                            <option value="intense">Intense (10+ hrs/week)</option>
                        </select>
                        @error('intensity') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Selected Days -->
                <div>
                    <label class="block text-sm font-medium text-stone mb-2">Study days *</label>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach(['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'] as $dayKey => $dayLabel)
                            <button 
                                type="button"
                                wire:click="$set('selectedDays', array_values(array_diff({{ json_encode($selectedDays) }}, ['{{ $dayKey }}']))) || $set('selectedDays', array_merge({{ json_encode($selectedDays) }}, ['{{ $dayKey }}']))"
                                class="px-3 py-2 text-sm font-medium rounded-lg {{ in_array($dayKey, $selectedDays) ? 'bg-accent text-surface' : 'bg-paper text-stone border border-line hover:text-ink' }} transition-colors"
                            >
                                {{ $dayLabel }}
                            </button>
                        @endforeach
                    </div>
                    @error('selectedDays') <span class="text-wrong text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-line">
                    <button type="button" wire:click="closeModals" class="px-4 py-2 border border-line text-stone hover:text-ink hover:bg-paper rounded-lg transition-colors text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-ink text-surface rounded-lg hover:bg-stone transition-colors text-sm font-medium">Save schedule</button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('livewire:navigated', () => {
    // Reset modal state
});
</script>
@endpush
