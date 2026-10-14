<section id="faq" class="bg-paper py-20 sm:py-24 border-t border-[#E3DFD5]">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-left">
        <!-- Heading -->
        <div class="mb-12 sm:mb-14 flex items-center justify-between gap-6">
            <div>
                <span class="font-mono text-xs sm:text-sm text-accent font-semibold tracking-wide uppercase block mb-2">Got questions?</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-ink tracking-tight">Frequently Asked Questions</h2>
            </div>
            <div class="hidden sm:block shrink-0">
                <x-mascot variant="think" :size="96" class="w-[84px] h-[84px] md:w-[96px] md:h-[96px]" />
            </div>
        </div>

@php
    $appSettings = app(\App\Services\AppSettings::class);
    $customFaqs = $appSettings->get('welcome_faq_items');
    $customDisclaimer = $appSettings->get('welcome_disclaimer_text');

    $faqs = !empty($customFaqs) && is_array($customFaqs) ? $customFaqs : [
        [
            'question' => 'Is Paxi completely free?',
            'answer' => 'Yes, 100% free! All practice questions, mistake tracking, and timed mock assessments are available without any paywalls or subscriptions.',
        ],
        [
            'question' => 'Where do the practice questions come from?',
            'answer' => 'Questions are sourced and modeled directly after official ITPEC IT Passport past examination papers and the official syllabus standards.',
        ],
        [
            'question' => 'Is it affiliated with PhilNITS or ITPEC?',
            'answer' => $customDisclaimer ?: 'No. Paxi is an independent community study companion created to help examinees prepare effectively. It is not officially endorsed by PhilNITS or ITPEC.',
        ],
        [
            'question' => 'Can I install this on my mobile phone?',
            'answer' => 'Yes! Paxi is built as a Progressive Web App (PWA). Tap "Install App" or "Add to Home Screen" in your mobile browser to use it offline anytime.',
        ],
    ];
@endphp

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
            @foreach($faqs as $faq)
                <div class="bg-surface rounded-2xl border border-[#E3DFD5] p-6 sm:p-7 space-y-3 shadow-xs hover:border-accent/40 transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-tint text-accent flex items-center justify-center font-bold text-sm shrink-0">
                            ?
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-ink">{{ $faq['question'] }}</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-stone leading-relaxed pl-11">
                        {{ $faq['answer'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

