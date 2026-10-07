<div class="mt-[28px] relative z-10 max-w-[1100px] mx-auto px-5 sm:px-8" aria-hidden="true">
    <div class="bg-surface border border-[#CFCABD] rounded-[12px] overflow-hidden text-left shadow-xs">
        <!-- Title bar -->
        <div class="px-4 py-2.5 bg-surface border-b border-[#E3DFD5] flex items-center space-x-3">
            <div class="flex items-center space-x-1.5 shrink-0">
                <span class="w-2 h-2 rounded-full bg-[#E3DFD5]"></span>
                <span class="w-2 h-2 rounded-full bg-[#E3DFD5]"></span>
                <span class="w-2 h-2 rounded-full bg-[#E3DFD5]"></span>
            </div>
            <span class="font-mono text-xs text-stone truncate">practice / information technology fundamentals</span>
        </div>

        <!-- 3-Pane Layout -->
        <div class="flex flex-col sm:flex-row items-stretch">
            <!-- Left Pane: Topics (150px wide, hidden on mobile) -->
            <div class="hidden sm:block w-[150px] shrink-0 bg-panel border-r border-[#E3DFD5] p-3.5 space-y-3">
                <span class="text-xs text-stone font-medium block mb-2">Topics</span>
                <div class="space-y-1">
                    <div class="px-2.5 py-1.5 rounded-[6px] bg-accent-tint text-accent font-medium text-xs leading-snug">
                        Information Technology Fundamentals
                    </div>
                    <div class="px-2.5 py-1.5 text-stone text-xs leading-snug">
                        Business and Management
                    </div>
                    <div class="px-2.5 py-1.5 text-stone text-xs leading-snug">
                        Technology
                    </div>
                    <div class="px-2.5 py-1.5 text-stone text-xs leading-snug">
                        Legal and Compliance
                    </div>
                </div>
            </div>

            <!-- Middle Pane: Question View (Flex-1) -->
            <div class="flex-1 min-w-0 p-4 sm:p-5 bg-surface space-y-4">
                <!-- Progress Header -->
                <div class="flex items-center justify-between gap-4">
                    <span class="font-mono text-xs text-stone shrink-0">Q07 / 10</span>
                    <div class="grow max-w-[200px] bg-[#E3DFD5] h-1 rounded-full overflow-hidden">
                        <div class="bg-accent h-full w-[70%]"></div>
                    </div>
                </div>

                <!-- Question Text -->
                <h3 class="text-[15px] font-medium text-ink leading-snug">
                    Which service translates a domain name into an IP address?
                </h3>

                <!-- Options Grid -->
                <div class="space-y-2 text-xs">
                    <!-- Option A (Wrong State) -->
                    <div class="px-3 py-2.5 rounded-[8px] bg-wrong-surface border border-wrong text-wrong flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-medium">A</span>
                            <span>DHCP</span>
                        </div>
                        <svg class="w-3.5 h-3.5 stroke-wrong shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <!-- Option B (Correct State) -->
                    <div class="px-3 py-2.5 rounded-[8px] bg-correct-surface border border-correct text-correct flex items-center justify-between font-medium">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono">B</span>
                            <span>DNS</span>
                        </div>
                        <svg class="w-3.5 h-3.5 stroke-correct shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <!-- Option C (Neutral State) -->
                    <div class="px-3 py-2.5 rounded-[8px] bg-surface border border-[#E3DFD5] text-stone flex items-center space-x-2">
                        <span class="font-mono text-stone">C</span>
                        <span>FTP</span>
                    </div>
                </div>
            </div>

            <!-- Right Pane: Explanation (170px wide, hidden on mobile) -->
            <div class="hidden sm:block w-[170px] shrink-0 bg-panel border-l border-[#E3DFD5] p-3.5 space-y-3">
                <span class="text-xs text-stone font-medium block mb-1">Explanation</span>
                <p class="text-xs text-stone leading-relaxed">
                    DNS maps names to IP addresses. DHCP hands out addresses to devices on a network.
                </p>
            </div>
        </div>
    </div>
</div>
