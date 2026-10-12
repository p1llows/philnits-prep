@props([
    'title' => 'Loading your progress...',
    'subtitle' => 'This won\'t take long!',
    'size' => 96,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-4 space-y-4']) }}>
    <div class="flex justify-center mb-3">
        <div class="relative">
            <x-mascot variant="avatar" :size="$size" label="Passy loading" class="animate-pulse" />
        </div>
    </div>
    
    <div>
        <h3 class="text-lg sm:text-xl font-bold text-ink tracking-tight">{{ $title }}</h3>
        <p class="text-xs sm:text-sm text-stone mt-1">{{ $subtitle }}</p>
    </div>

    <!-- Bouncing Dots (mockup style) -->
    <div class="flex items-center justify-center space-x-1.5 pt-2">
        <span class="w-2.5 h-2.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0s;"></span>
        <span class="w-2.5 h-2.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.15s;"></span>
        <span class="w-2.5 h-2.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.3s;"></span>
    </div>
</div>
