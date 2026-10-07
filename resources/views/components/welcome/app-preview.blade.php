<div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-hidden="true">
    <div class="bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden text-left shadow-sm hover:shadow-lg hover:border-accent/40 transition-all duration-300 group">
        <!-- Title bar -->
        <div class="px-5 py-3 bg-surface border-b border-[#E3DFD5] flex items-center space-x-3">
            <div class="flex items-center space-x-1.5 shrink-0">
                <span class="w-2.5 h-2.5 rounded-full bg-[#E3DFD5] group-hover:bg-red-400/60 transition-colors duration-300"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#E3DFD5] group-hover:bg-amber-400/60 transition-colors duration-300"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#E3DFD5] group-hover:bg-emerald-400/60 transition-colors duration-300"></span>
            </div>
            <span class="font-mono text-xs sm:text-sm text-stone truncate">practice / information technology fundamentals</span>
        </div>

        <!-- 3-Pane Layout -->
        <div class="flex flex-col sm:flex-row items-stretch min-h-[300px]">
            <!-- Left Pane: Topics (180px - 200px wide) -->
            <div class="hidden sm:block w-[180px] lg:w-[200px] shrink-0 bg-panel border-r border-[#E3DFD5] p-4 space-y-3">
                <span class="text-xs text-stone font-medium block mb-2">Topics</span>
                <div class="space-y-1">
                    <div class="px-3 py-2 rounded-md bg-accent-tint text-accent font-medium text-xs leading-snug">
                        Information Technology Fundamentals
                    </div>
                    <div class="px-3 py-2 text-stone text-xs leading-snug">
                        Business and Management
                    </div>
                    <div class="px-3 py-2 text-stone text-xs leading-snug">
                        Technology
                    </div>
                    <div class="px-3 py-2 text-stone text-xs leading-snug">
                        Legal and Compliance
                    </div>
                </div>
            </div>

            <!-- Middle Pane: Question View (Flex-1, Spacious Padding & min-width) -->
            <div class="flex-1 min-w-0 p-6 sm:p-8 bg-surface space-y-5">
                <!-- Progress Header -->
                <div class="flex items-center justify-between gap-4">
                    <span class="font-mono text-xs sm:text-sm text-stone shrink-0">Q07 / 10</span>
                    <div class="grow max-w-[240px] bg-[#E3DFD5] h-1.5 rounded-full overflow-hidden">
                        <div class="bg-accent h-full w-[70%] rounded-full"></div>
                    </div>
                </div>

                <!-- Question Text -->
                <h3 class="text-base sm:text-lg lg:text-xl font-medium text-ink leading-snug">
                    Which service translates a domain name into an IP address?
                </h3>

                <!-- Options Grid -->
                <div class="space-y-2.5 text-xs sm:text-sm">
                    <!-- Option A (Wrong State) -->
                    <div class="px-4 py-3 rounded-xl bg-wrong-surface border border-wrong text-wrong flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-medium">A</span>
                            <span class="font-medium">DHCP</span>
                        </div>
                        <svg class="w-4 h-4 stroke-wrong shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <!-- Option B (Correct State) -->
                    <div class="px-4 py-3 rounded-xl bg-correct-surface border border-correct text-correct flex items-center justify-between font-medium">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono">B</span>
                            <span class="font-medium">DNS</span>
                        </div>
                        <svg class="w-4 h-4 stroke-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <!-- Option C (Neutral State) -->
                    <div class="px-4 py-3 rounded-xl bg-surface border border-[#E3DFD5] text-stone flex items-center space-x-3">
                        <span class="font-mono text-stone">C</span>
                        <span>FTP</span>
                    </div>
                </div>
            </div>

            <!-- Right Pane: Explanation (200px - 220px wide) -->
            <div class="hidden sm:block w-[200px] lg:w-[220px] shrink-0 bg-panel border-l border-[#E3DFD5] p-4 space-y-3">
                <span class="text-xs text-stone font-medium block mb-1">Explanation</span>
                <p class="text-xs sm:text-sm text-stone leading-relaxed">
                    DNS maps names to IP addresses. DHCP hands out addresses to devices on a network.
                </p>
            </div>
        </div>
    </div>
</div>
