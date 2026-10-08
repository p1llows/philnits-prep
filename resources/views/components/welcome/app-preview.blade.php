<div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ tab: 'practice', selectedOption: 'B', showExplanation: true }">
    <!-- Ambient Glow behind window frame -->
    <div class="absolute -inset-1.5 bg-gradient-to-r from-accent/15 via-accent/5 to-accent/20 rounded-3xl blur-xl opacity-80 pointer-events-none"></div>

    <div class="relative bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden text-left shadow-lg transition-all duration-300">
        <!-- Title & Tab Bar Window Chrome -->
        <div class="px-4 sm:px-5 py-3 bg-surface border-b border-[#E3DFD5] flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-1.5 shrink-0">
                    <span class="w-3 h-3 rounded-full bg-[#E3DFD5] hover:bg-red-400 transition-colors duration-200 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-[#E3DFD5] hover:bg-amber-400 transition-colors duration-200 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-[#E3DFD5] hover:bg-emerald-400 transition-colors duration-200 inline-block"></span>
                </div>
                <span class="font-mono text-xs text-stone truncate hidden sm:inline-block">philnits-prep.app</span>
            </div>

            <!-- Tab Switchers -->
            <div class="flex items-center space-x-1 bg-paper p-1 rounded-xl border border-[#E3DFD5] text-xs font-medium">
                <button @click="tab = 'practice'" type="button" 
                        :class="tab === 'practice' ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink'"
                        class="px-3 py-1.5 rounded-lg transition-all flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Practice Mode</span>
                </button>
                <button @click="tab = 'mistakes'" type="button" 
                        :class="tab === 'mistakes' ? 'bg-surface text-wrong shadow-xs font-semibold' : 'text-stone hover:text-ink'"
                        class="px-3 py-1.5 rounded-lg transition-all flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Mistake Bank</span>
                </button>
                <button @click="tab = 'assessment'" type="button" 
                        :class="tab === 'assessment' ? 'bg-surface text-accent shadow-xs font-semibold' : 'text-stone hover:text-ink'"
                        class="px-3 py-1.5 rounded-lg transition-all flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Timed Assessment</span>
                </button>
            </div>
        </div>

        <!-- TAB 1: PRACTICE MODE -->
        <div x-show="tab === 'practice'" class="flex flex-col sm:flex-row items-stretch min-h-[340px]">
            <!-- Left Sidebar Topics -->
            <div class="hidden sm:block w-[190px] lg:w-[210px] shrink-0 bg-panel border-r border-[#E3DFD5] p-4 space-y-3">
                <span class="text-xs font-mono text-stone font-medium block mb-2 uppercase tracking-wider">Exam Fields</span>
                <div class="space-y-1 text-xs">
                    <div class="px-3 py-2 rounded-lg bg-accent-tint text-accent font-semibold flex items-center justify-between">
                        <span>Field 03: Technology</span>
                        <span class="w-2 h-2 rounded-full bg-accent"></span>
                    </div>
                    <div class="px-3 py-2 rounded-lg text-stone hover:bg-paper transition-colors">
                        Field 01: Strategy
                    </div>
                    <div class="px-3 py-2 rounded-lg text-stone hover:bg-paper transition-colors">
                        Field 02: Management
                    </div>
                    <div class="px-3 py-2 rounded-lg text-stone hover:bg-paper transition-colors">
                        Legal & Compliance
                    </div>
                </div>
            </div>

            <!-- Middle Question View -->
            <div class="flex-1 min-w-0 p-6 sm:p-8 bg-surface space-y-5">
                <!-- Progress Header -->
                <div class="flex items-center justify-between gap-4 text-xs font-mono">
                    <span class="text-accent font-bold">Q07 / 10 · Network Fundamentals</span>
                    <div class="grow max-w-[200px] bg-[#E3DFD5] h-1.5 rounded-full overflow-hidden">
                        <div class="bg-accent h-full w-[70%] rounded-full"></div>
                    </div>
                    <span class="text-stone hidden lg:inline-block">Accuracy: 85%</span>
                </div>

                <!-- Question Text -->
                <h3 class="text-base sm:text-lg lg:text-xl font-medium text-ink leading-snug">
                    Which network service is primarily responsible for resolving human-readable domain names into IP addresses?
                </h3>

                <!-- Clickable Interactive Options -->
                <div class="space-y-2.5 text-xs sm:text-sm">
                    <!-- Option A -->
                    <button @click="selectedOption = 'A'" type="button"
                            :class="selectedOption === 'A' ? 'bg-wrong-surface border-wrong text-wrong' : 'bg-surface border-[#E3DFD5] text-stone hover:border-stone-400'"
                            class="w-full px-4 py-3 rounded-xl border text-left flex items-center justify-between transition-all duration-150">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-bold w-5">A</span>
                            <span class="font-medium">DHCP (Dynamic Host Configuration Protocol)</span>
                        </div>
                        <template x-if="selectedOption === 'A'">
                            <svg class="w-4 h-4 stroke-wrong shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                    </button>

                    <!-- Option B (Correct) -->
                    <button @click="selectedOption = 'B'" type="button"
                            :class="selectedOption === 'B' ? 'bg-correct-surface border-correct text-correct font-semibold' : 'bg-surface border-[#E3DFD5] text-stone hover:border-stone-400'"
                            class="w-full px-4 py-3 rounded-xl border text-left flex items-center justify-between transition-all duration-150">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-bold w-5">B</span>
                            <span>DNS (Domain Name System)</span>
                        </div>
                        <template x-if="selectedOption === 'B'">
                            <span class="flex items-center space-x-1 text-correct text-xs font-mono font-bold">
                                <span>Correct</span>
                                <svg class="w-4 h-4 stroke-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </template>
                    </button>

                    <!-- Option C -->
                    <button @click="selectedOption = 'C'" type="button"
                            :class="selectedOption === 'C' ? 'bg-wrong-surface border-wrong text-wrong' : 'bg-surface border-[#E3DFD5] text-stone hover:border-stone-400'"
                            class="w-full px-4 py-3 rounded-xl border text-left flex items-center justify-between transition-all duration-150">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-bold w-5">C</span>
                            <span>FTP (File Transfer Protocol)</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Right Explanation Pane -->
            <div class="hidden sm:block w-[210px] lg:w-[240px] shrink-0 bg-panel border-l border-[#E3DFD5] p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-accent font-bold block uppercase tracking-wider">Explanation</span>
                    <span class="text-[10px] bg-correct-surface text-correct px-2 py-0.5 rounded font-mono">Verified</span>
                </div>
                <p class="text-xs text-stone leading-relaxed">
                    <strong>DNS</strong> maps human-friendly hostnames to IP addresses. 
                    <strong>DHCP</strong> automatically assigns IP configurations to clients, while <strong>FTP</strong> transfers files.
                </p>
                <div class="pt-2 border-t border-[#E3DFD5]">
                    <span class="text-[11px] text-stone font-medium block">Key Takeaway:</span>
                    <span class="text-xs text-ink">ITPEC exams frequently test port & protocol roles.</span>
                </div>
            </div>
        </div>

        <!-- TAB 2: MISTAKE BANK -->
        <div x-show="tab === 'mistakes'" x-cloak class="p-6 sm:p-8 bg-surface space-y-6 min-h-[340px]">
            <div class="flex items-center justify-between pb-4 border-b border-[#E3DFD5]">
                <div>
                    <h4 class="text-lg font-bold text-ink">Mistake Review Queue</h4>
                    <p class="text-xs text-stone mt-0.5">Incorrectly answered questions automatically queue here until you get them right twice.</p>
                </div>
                <span class="bg-wrong-surface text-wrong font-mono text-xs px-3 py-1.5 rounded-lg font-bold">
                    3 items in queue
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-wrong/40 bg-wrong-surface/40 space-y-2">
                    <div class="flex items-center justify-between text-xs font-mono text-wrong font-semibold">
                        <span>Field 03 · Network Security</span>
                        <span>Missed 2x</span>
                    </div>
                    <p class="text-sm font-medium text-ink">What is the main function of a PKI (Public Key Infrastructure) digital certificate?</p>
                    <div class="pt-2 flex items-center justify-between text-xs">
                        <span class="text-stone">Last attempted yesterday</span>
                        <span class="text-accent font-semibold hover:underline cursor-pointer">Practice this &rarr;</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl border border-[#E3DFD5] bg-paper space-y-2">
                    <div class="flex items-center justify-between text-xs font-mono text-stone font-semibold">
                        <span>Field 01 · Corporate Law</span>
                        <span>Missed 1x</span>
                    </div>
                    <p class="text-sm font-medium text-ink">Which intellectual property right protects software source code formatting?</p>
                    <div class="pt-2 flex items-center justify-between text-xs">
                        <span class="text-stone">Last attempted 3 days ago</span>
                        <span class="text-accent font-semibold hover:underline cursor-pointer">Practice this &rarr;</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: TIMED ASSESSMENT -->
        <div x-show="tab === 'assessment'" x-cloak class="p-6 sm:p-8 bg-surface space-y-6 min-h-[340px]">
            <div class="flex items-center justify-between pb-4 border-b border-[#E3DFD5]">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 rounded-full bg-red-500 animate-ping"></div>
                    <div>
                        <h4 class="text-lg font-bold text-ink">IT Passport Official Mock Exam</h4>
                        <p class="text-xs text-stone">100 questions · 120 minutes · Passing score: 600/1000 pts</p>
                    </div>
                </div>
                <div class="font-mono text-sm sm:text-base font-bold text-accent bg-accent-tint px-3.5 py-1.5 rounded-xl">
                    ⏱️ 01:42:15
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <span class="text-xs font-mono text-stone">Question 42 of 100</span>
                    <p class="text-sm sm:text-base font-medium text-ink">
                        In project management under PMBOK guidelines, which document formally authorizes the existence of a project?
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-lg border border-[#E3DFD5] text-stone">A. Work Breakdown Structure (WBS)</div>
                        <div class="p-2.5 rounded-lg border border-accent bg-accent-tint text-accent font-semibold">B. Project Charter</div>
                        <div class="p-2.5 rounded-lg border border-[#E3DFD5] text-stone">C. Scope Statement</div>
                        <div class="p-2.5 rounded-lg border border-[#E3DFD5] text-stone">D. Risk Register</div>
                    </div>
                </div>
                <div class="bg-panel p-4 rounded-xl border border-[#E3DFD5] space-y-3">
                    <span class="text-xs font-mono text-stone font-medium block">Question Navigator</span>
                    <div class="grid grid-cols-5 gap-1.5 text-center text-xs font-mono">
                        <span class="p-1 rounded bg-correct-surface text-correct font-bold">40</span>
                        <span class="p-1 rounded bg-correct-surface text-correct font-bold">41</span>
                        <span class="p-1 rounded bg-accent text-surface font-bold">42</span>
                        <span class="p-1 rounded bg-paper text-stone">43</span>
                        <span class="p-1 rounded bg-paper text-stone">44</span>
                    </div>
                    <div class="text-[11px] text-stone pt-2 border-t border-[#E3DFD5] flex items-center justify-between">
                        <span>Answered: 41/100</span>
                        <span class="text-correct font-semibold">On Track</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

