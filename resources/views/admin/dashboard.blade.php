<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Alert -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                <div class="flex">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <div>
                        <h3 class="font-medium text-gray-900">Administrative Area</h3>
                        <p class="text-sm text-gray-700 mt-1">Manage topics, questions, source packages, and user access.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Topics Management -->
                <a href="{{ route('admin.topics.index') }}" 
                   class="group block bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">Topics</h3>
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Create, edit, or delete examination topics</p>
                    <div class="mt-4 flex items-center text-sm text-blue-600">
                        Manage Topics →
                    </div>
                </a>

                <!-- Questions Management (Active) -->
                <a href="{{ route('admin.questions.index') }}" 
                   class="group block bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Questions</h3>
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Add or modify examination questions</p>
                    <div class="mt-4 flex items-center text-sm text-indigo-600 font-medium">
                        Manage Questions →
                    </div>
                </a>

                <!-- Source Packages (Coming Soon) -->
                <div class="block bg-gray-50 rounded-xl shadow-sm p-6 opacity-75 cursor-not-allowed"
                     title="Feature coming in next phase">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-500">Source Packages</h3>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Import and manage official source materials</p>
                    <div class="mt-4 inline-flex items-center px-2 py-1 bg-gray-200 text-xs font-medium rounded text-gray-600">
                        Coming Soon
                    </div>
                </div>

                <!-- Analytics (Active) -->
                <a href="{{ route('analytics.index') }}" 
                   class="group block bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600">Analytics</h3>
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">View progress analytics and performance metrics</p>
                    <div class="mt-4 flex items-center text-sm text-green-600">
                        View Analytics →
                    </div>
                </a>

                <!-- Assessment Reports (Coming Soon) -->
                <div class="block bg-gray-50 rounded-xl shadow-sm p-6 opacity-75 cursor-not-allowed"
                     title="Feature coming in next phase">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-500">Reports</h3>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Export assessment reports and statistics</p>
                    <div class="mt-4 inline-flex items-center px-2 py-1 bg-gray-200 text-xs font-medium rounded text-gray-600">
                        Coming Soon
                    </div>
                </div>

                <!-- System Settings (Coming Soon) -->
                <div class="block bg-gray-50 rounded-xl shadow-sm p-6 opacity-75 cursor-not-allowed"
                     title="Feature coming in next phase">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-500">Settings</h3>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Configure system settings and preferences</p>
                    <div class="mt-4 inline-flex items-center px-2 py-1 bg-gray-200 text-xs font-medium rounded text-gray-600">
                        Coming Soon
                    </div>
                </div>

            </div>

            <!-- Admin Statistics Overview -->
            <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">System Overview</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    
                    <!-- Total Topics -->
                    <div class="p-6 text-center">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Topics</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Topic::count() }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ \App\Models\Topic::where('is_active', true)->where('code', 'LIKE', 'PH%')->count() }} active</p>
                    </div>

                    <!-- Published Questions -->
                    <div class="p-6 text-center">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Published Questions</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Question::published()->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Question::unpublished()->count() }} pending review</p>
                    </div>

                    <!-- Active Assessments -->
                    <div class="p-6 text-center">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Assessments Taken</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Assessment::count() }}</p>
                        <p class="text-xs text-blue-600 mt-1">Last {{ \App\Models\Assessment::oldest()->first()?->created_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>

                    <!-- Average Score -->
                    <div class="p-6 text-center">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Avg. Score</p>
                        @if(\App\Models\Assessment::count() > 0)
                            <p class="mt-2 text-3xl font-bold text-primary-600">
                                {{ round(\App\Models\Assessment::avg('score'), 1) }}%
                            </p>
                        @else
                            <p class="mt-2 text-3xl font-bold text-gray-900">-</p>
                            <p class="text-xs text-gray-500 mt-1">Take first assessment</p>
                        @endif
                        <p class="text-xs text-green-600 mt-1">{{ \App\Models\Assessment::where('score', '>=', 60)->count() }} passed (60%)</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
