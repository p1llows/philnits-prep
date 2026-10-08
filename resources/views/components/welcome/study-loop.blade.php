<section id="study-loop" class="relative py-20 sm:py-24 bg-paper border-t border-[#E3DFD5]">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-left">
        <!-- Section Header -->
        <div class="mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-ink tracking-tight mb-2">
                A simple study loop
            </h2>
            <p class="text-base sm:text-lg text-stone">
                Practice, go back over what you missed, then test yourself.
            </p>
        </div>

        <!-- 3 Step Columns Grid with Continuous Stepper Line -->
        <div class="relative">
            <!-- Continuous connected line spanning across all 3 dots -->
            <div class="hidden md:block absolute top-[7px] left-0 right-0 h-[2px] bg-[#E3DFD5] pointer-events-none z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-12 relative z-10">
                
                <!-- Step 01 -->
                <div class="group cursor-pointer flex flex-col justify-between space-y-6">
                    <div>
                        <!-- Stepper Node -->
                        <div class="flex items-center mb-6">
                            <div class="w-4 h-4 rounded-full bg-accent border-2 border-accent z-10 shrink-0 group-hover:scale-125 group-hover:ring-4 group-hover:ring-accent/20 transition-all duration-300"></div>
                        </div>

                        <!-- Step Text -->
                        <span class="font-mono text-xs sm:text-sm text-stone group-hover:text-accent font-medium block mb-1 transition-colors duration-200">01</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-ink group-hover:text-accent mb-2 transition-colors duration-200">Practice by topic</h3>
                        <p class="text-sm text-stone leading-relaxed">
                            Answer questions with feedback after each one.
                        </p>
                    </div>

                    <!-- Mock Widget Card 1 -->
                    <div class="bg-surface rounded-2xl border border-[#CFCABD] p-5 space-y-3 shadow-xs group-hover:-translate-y-1.5 group-hover:shadow-md group-hover:border-accent/50 transition-all duration-300">
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <span class="font-medium text-ink group-hover:text-accent transition-colors duration-200">Networking</span>
                            <span class="font-mono text-stone">6 / 10</span>
                        </div>
                        <div class="w-full bg-[#E3DFD5] h-1.5 rounded-full overflow-hidden">
                            <div class="bg-accent h-full w-[60%] group-hover:w-[85%] rounded-full transition-all duration-700 ease-out"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="group cursor-pointer flex flex-col justify-between space-y-6">
                    <div>
                        <!-- Stepper Node -->
                        <div class="flex items-center mb-6">
                            <div class="w-4 h-4 rounded-full bg-paper border-2 border-accent z-10 shrink-0 group-hover:bg-accent group-hover:scale-125 group-hover:ring-4 group-hover:ring-accent/20 transition-all duration-300"></div>
                        </div>

                        <!-- Step Text -->
                        <span class="font-mono text-xs sm:text-sm text-stone group-hover:text-accent font-medium block mb-1 transition-colors duration-200">02</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-ink group-hover:text-accent mb-2 transition-colors duration-200">Mistake review</h3>
                        <p class="text-sm text-stone leading-relaxed">
                            Every wrong answer is kept for later.
                        </p>
                    </div>

                    <!-- Mock Widget Card 2 -->
                    <div class="bg-surface rounded-2xl border border-[#CFCABD] p-5 space-y-2.5 shadow-xs group-hover:-translate-y-1.5 group-hover:shadow-md group-hover:border-wrong/50 transition-all duration-300">
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <div class="flex items-center space-x-3">
                                <span class="font-mono text-stone text-xs">Q07</span>
                                <span class="font-semibold text-ink">DNS</span>
                            </div>
                            <span class="text-wrong font-bold text-base leading-none group-hover:scale-125 transition-transform duration-200 inline-block">×</span>
                        </div>
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <div class="flex items-center space-x-3">
                                <span class="font-mono text-stone text-xs">Q12</span>
                                <span class="font-semibold text-ink">RAID</span>
                            </div>
                            <span class="text-wrong font-bold text-base leading-none group-hover:scale-125 transition-transform duration-200 inline-block">×</span>
                        </div>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="group cursor-pointer flex flex-col justify-between space-y-6">
                    <div>
                        <!-- Stepper Node -->
                        <div class="flex items-center mb-6">
                            <div class="w-4 h-4 rounded-full bg-paper border-2 border-accent z-10 shrink-0 group-hover:bg-accent group-hover:scale-125 group-hover:ring-4 group-hover:ring-accent/20 transition-all duration-300"></div>
                        </div>

                        <!-- Step Text -->
                        <span class="font-mono text-xs sm:text-sm text-stone group-hover:text-accent font-medium block mb-1 transition-colors duration-200">03</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-ink group-hover:text-accent mb-2 transition-colors duration-200">Timed assessments</h3>
                        <p class="text-sm text-stone leading-relaxed">
                            A timer, no hints, a score at the end.
                        </p>
                    </div>

                    <!-- Mock Widget Card 3 -->
                    <div class="bg-surface rounded-2xl border border-[#CFCABD] p-5 space-y-3 shadow-xs group-hover:-translate-y-1.5 group-hover:shadow-md group-hover:border-accent/50 transition-all duration-300">
                        <div class="font-mono text-lg sm:text-xl font-medium text-ink tracking-widest group-hover:text-accent transition-colors duration-200">
                            01:42:10
                        </div>
                        <div class="w-full bg-[#E3DFD5] h-1.5 rounded-full overflow-hidden">
                            <div class="bg-accent h-full w-[20%] group-hover:w-[50%] rounded-full transition-all duration-700 ease-out"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>



