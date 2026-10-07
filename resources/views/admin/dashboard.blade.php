<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Alert Banner -->
            <div class="bg-surface border border-line p-5 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-ink mr-3 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <div>
                        <h3 class="font-medium text-ink text-base">Administrative Area</h3>
                        <p class="text-sm text-stone mt-1">Manage topics, questions, source packages, and user access.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Topics Management -->
                <a href="{{ route('admin.topics.index') }}" 
                   class="group block bg-surface rounded-xl border border-line p-5 hover:border-accent transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-ink group-hover:text-accent">Topics</h3>
                        <svg class="w-6 h-6 text-stone group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">Create, edit, or delete examination topics</p>
                    <div class="mt-4 flex items-center text-sm text-accent font-medium">
                        Manage topics →
                    </div>
                </a>

                <!-- Questions Management (Active) -->
                <a href="{{ route('admin.questions.index') }}" 
                   class="group block bg-surface rounded-xl border border-line p-5 hover:border-accent transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-ink group-hover:text-accent">Questions</h3>
                        <svg class="w-6 h-6 text-stone group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">Add or modify examination questions</p>
                    <div class="mt-4 flex items-center text-sm text-accent font-medium">
                        Manage questions →
                    </div>
                </a>

                <!-- Source Packages (Active) -->
                <a href="{{ route('admin.source-packages.index') }}" 
                   class="group block bg-surface rounded-xl border border-line p-5 hover:border-accent transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-ink group-hover:text-accent">Source packages</h3>
                        <svg class="w-6 h-6 text-stone group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">Import and manage official source materials</p>
                    <div class="mt-4 flex items-center text-sm text-accent font-medium">
                        Manage source packages →
                    </div>
                </a>

                <!-- Analytics (Active) -->
                <a href="{{ route('analytics.index') }}" 
                   class="group block bg-surface rounded-xl border border-line p-5 hover:border-accent transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-ink group-hover:text-accent">Analytics</h3>
                        <svg class="w-6 h-6 text-stone group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">View progress analytics and performance metrics</p>
                    <div class="mt-4 flex items-center text-sm text-accent font-medium">
                        View analytics →
                    </div>
                </a>

                <!-- Assessment Reports (Coming Soon) -->
                <div class="block bg-paper/50 rounded-xl border border-dashed border-line p-5 cursor-not-allowed"
                     title="Feature coming in next phase">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-stone">Reports</h3>
                        <svg class="w-6 h-6 text-stone/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">Export assessment reports and statistics</p>
                    <div class="mt-4 inline-flex items-center px-2 py-1 bg-surface border border-line text-xs font-medium rounded text-stone">
                        Coming soon
                    </div>
                </div>

                <!-- System Settings (Coming Soon) -->
                <div class="block bg-paper/50 rounded-xl border border-dashed border-line p-5 cursor-not-allowed"
                     title="Feature coming in next phase">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-stone">Settings</h3>
                        <svg class="w-6 h-6 text-stone/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-stone">Configure system settings and preferences</p>
                    <div class="mt-4 inline-flex items-center px-2 py-1 bg-surface border border-line text-xs font-medium rounded text-stone">
                        Coming soon
                    </div>
                </div>

            </div>

            <!-- Admin Statistics Overview -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="px-5 py-4 border-b border-line bg-paper/30">
                    <h3 class="text-base font-medium text-ink">System overview</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-line">
                    
                    <!-- Total Topics -->
                    <div class="p-5 text-center">
                        <p class="text-xs font-medium text-stone">Total topics</p>
                        <p class="mt-2 text-3xl font-semibold text-ink">{{ \App\Models\Topic::count() }}</p>
                        <p class="text-xs text-stone mt-1">{{ \App\Models\Topic::where('is_active', true)->where('code', 'LIKE', 'PH%')->count() }} active</p>
                    </div>

                    <!-- Published Questions -->
                    <div class="p-5 text-center">
                        <p class="text-xs font-medium text-stone">Published questions</p>
                        <p class="mt-2 text-3xl font-semibold text-ink">{{ \App\Models\Question::published()->count() }}</p>
                        <p class="text-xs text-stone mt-1">{{ \App\Models\Question::unpublished()->count() }} pending review</p>
                    </div>

                    <!-- Active Assessments -->
                    <div class="p-5 text-center">
                        <p class="text-xs font-medium text-stone">Assessments taken</p>
                        <p class="mt-2 text-3xl font-semibold text-ink">{{ \App\Models\Assessment::count() }}</p>
                        <p class="text-xs text-accent mt-1">Last {{ \App\Models\Assessment::oldest()->first()?->created_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>

                    <!-- Average Score -->
                    <div class="p-5 text-center">
                        <p class="text-xs font-medium text-stone">Average score</p>
                        @if(\App\Models\Assessment::count() > 0)
                            <p class="mt-2 text-3xl font-semibold text-accent">
                                {{ round(\App\Models\Assessment::avg('score'), 1) }}%
                            </p>
                        @else
                            <p class="mt-2 text-3xl font-semibold text-ink">-</p>
                            <p class="text-xs text-stone mt-1">Take first assessment</p>
                        @endif
                        <p class="text-xs text-stone mt-1">{{ \App\Models\Assessment::where('score', '>=', 60)->count() }} passed (60%)</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

