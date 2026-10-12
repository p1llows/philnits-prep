<x-app-layout>
    <div class="space-y-6 sm:space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Navy Hero Banner -->
        <div class="bg-accent rounded-3xl p-6 sm:p-8 relative overflow-hidden text-left shadow-lg">
            <svg aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full opacity-40">
                <defs>
                    <pattern id="grid-admin" width="32" height="32" patternUnits="userSpaceOnUse">
                        <path d="M32 0H0V32" fill="none" stroke="#2D4C7A" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-admin)"/>
            </svg>

            <div class="relative z-10 space-y-2 max-w-xl">
                <span class="inline-flex items-center space-x-2 bg-[#2D4C7A] text-[#A5B8D3] text-xs font-mono px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Administrative Area</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-paper tracking-tight">
                    System Control & Examination Management
                </h2>
                <p class="text-xs sm:text-sm text-[#A5B8D3]">
                    Manage topics, questions, source packages, and examinee review performance.
                </p>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Topics Management -->
            <a href="{{ route('admin.topics.index') }}" 
               class="group block bg-surface rounded-2xl border border-[#CFCABD] p-6 hover:border-accent hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-accent-tint text-accent flex items-center justify-center font-bold text-sm shrink-0 border border-accent/20 group-hover:bg-accent group-hover:text-surface transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-stone group-hover:text-accent font-semibold">&rarr;</span>
                </div>
                <h3 class="text-lg font-bold text-ink group-hover:text-accent transition-colors">Topics</h3>
                <p class="text-xs sm:text-sm text-stone mt-1">Create, edit, or delete examination syllabus topics.</p>
                <div class="mt-4 flex items-center text-xs text-accent font-bold">
                    Manage topics &rarr;
                </div>
            </a>

            <!-- Questions Management (Active) -->
            <a href="{{ route('admin.questions.index') }}" 
               class="group block bg-surface rounded-2xl border border-[#CFCABD] p-6 hover:border-accent hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-accent-tint text-accent flex items-center justify-center font-bold text-sm shrink-0 border border-accent/20 group-hover:bg-accent group-hover:text-surface transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-stone group-hover:text-accent font-semibold">&rarr;</span>
                </div>
                <h3 class="text-lg font-bold text-ink group-hover:text-accent transition-colors">Questions</h3>
                <p class="text-xs sm:text-sm text-stone mt-1">Add, modify, and review past examination questions.</p>
                <div class="mt-4 flex items-center text-xs text-accent font-bold">
                    Manage questions &rarr;
                </div>
            </a>

            <!-- Source Packages (Active) -->
            <a href="{{ route('admin.source-packages.index') }}" 
               class="group block bg-surface rounded-2xl border border-[#CFCABD] p-6 hover:border-accent hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-accent-tint text-accent flex items-center justify-center font-bold text-sm shrink-0 border border-accent/20 group-hover:bg-accent group-hover:text-surface transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-stone group-hover:text-accent font-semibold">&rarr;</span>
                </div>
                <h3 class="text-lg font-bold text-ink group-hover:text-accent transition-colors">Source packages</h3>
                <p class="text-xs sm:text-sm text-stone mt-1">Import and manage official ITPEC past exam sets.</p>
                <div class="mt-4 flex items-center text-xs text-accent font-bold">
                    Manage source packages &rarr;
                </div>
            </a>

            <!-- Analytics (Active) -->
            <a href="{{ route('analytics.index') }}" 
               class="group block bg-surface rounded-2xl border border-[#CFCABD] p-6 hover:border-accent hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-accent-tint text-accent flex items-center justify-center font-bold text-sm shrink-0 border border-accent/20 group-hover:bg-accent group-hover:text-surface transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-stone group-hover:text-accent font-semibold">&rarr;</span>
                </div>
                <h3 class="text-lg font-bold text-ink group-hover:text-accent transition-colors">Analytics</h3>
                <p class="text-xs sm:text-sm text-stone mt-1">View progress analytics and performance metrics.</p>
                <div class="mt-4 flex items-center text-xs text-accent font-bold">
                    View analytics &rarr;
                </div>
            </a>

            <!-- Assessment Reports (Coming Soon) -->
            <div class="block bg-paper/60 rounded-2xl border border-dashed border-[#CFCABD] p-6 cursor-not-allowed">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-medium text-stone">Reports</h3>
                    <svg class="w-5 h-5 text-stone/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-xs text-stone">Export assessment reports and statistics.</p>
                <div class="mt-4 inline-flex items-center px-2.5 py-1 bg-surface border border-[#D9D5C9] text-[11px] font-mono rounded text-stone">
                    Coming soon
                </div>
            </div>

            <!-- System Settings (Coming Soon) -->
            <div class="block bg-paper/60 rounded-2xl border border-dashed border-[#CFCABD] p-6 cursor-not-allowed">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-medium text-stone">Settings</h3>
                    <svg class="w-5 h-5 text-stone/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                </div>
                <p class="text-xs text-stone">Configure system settings and preferences.</p>
                <div class="mt-4 inline-flex items-center px-2.5 py-1 bg-surface border border-[#D9D5C9] text-[11px] font-mono rounded text-stone">
                    Coming soon
                </div>
            </div>

        </div>

        <!-- Admin Statistics Overview -->
        <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-[#E3DFD5] bg-panel">
                <h3 class="text-base font-bold text-ink">System Overview</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-[#E3DFD5]">
                
                <!-- Total Topics -->
                <div class="p-6 text-center">
                    <p class="text-xs font-mono uppercase tracking-wider text-stone font-semibold">Total topics</p>
                    <p class="mt-2 text-4xl font-extrabold text-ink">{{ \App\Models\Topic::count() }}</p>
                    <p class="text-xs text-stone mt-1">{{ \App\Models\Topic::where('is_active', true)->where('code', 'LIKE', 'PH%')->count() }} active</p>
                </div>

                <!-- Published Questions -->
                <div class="p-6 text-center">
                    <p class="text-xs font-mono uppercase tracking-wider text-stone font-semibold">Published questions</p>
                    <p class="mt-2 text-4xl font-extrabold text-ink">{{ \App\Models\Question::published()->count() }}</p>
                    <p class="text-xs text-stone mt-1">{{ \App\Models\Question::unpublished()->count() }} pending review</p>
                </div>

                <!-- Active Assessments -->
                <div class="p-6 text-center">
                    <p class="text-xs font-mono uppercase tracking-wider text-stone font-semibold">Assessments taken</p>
                    <p class="mt-2 text-4xl font-extrabold text-ink">{{ \App\Models\Assessment::count() }}</p>
                    <p class="text-xs text-accent mt-1">Last {{ \App\Models\Assessment::oldest()->first()?->created_at?->diffForHumans() ?? 'N/A' }}</p>
                </div>

                <!-- Average Score -->
                <div class="p-6 text-center">
                    <p class="text-xs font-mono uppercase tracking-wider text-stone font-semibold">Average score</p>
                    @if(\App\Models\Assessment::count() > 0)
                        <p class="mt-2 text-4xl font-extrabold text-accent">
                            {{ round(\App\Models\Assessment::avg('score'), 1) }}%
                        </p>
                    @else
                        <p class="mt-2 text-4xl font-extrabold text-ink">-</p>
                        <p class="text-xs text-stone mt-1">Take first assessment</p>
                    @endif
                    <p class="text-xs text-stone mt-1">{{ \App\Models\Assessment::where('score', '>=', 60)->count() }} passed (60%)</p>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>

