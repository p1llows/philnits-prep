<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1F3A5F">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Paxi') }}</title>

    @php
        $appSettings = app(\App\Services\AppSettings::class);
        $announcement = $appSettings->announcementBanner();
    @endphp

    <!-- Favicon & PWA -->
    <link rel="icon" href="{{ $appSettings->faviconUrl() }}" type="image/svg+xml">
    <link rel="icon" href="/favicon-32.png" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="theme-color" content="#1F3A5F">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500|source-serif-4:400,500&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-paper text-ink min-h-screen flex flex-col justify-between selection:bg-accent-tint selection:text-accent relative overflow-x-hidden">
    <!-- Graph Paper Grid Background matching Welcome Page -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <svg aria-hidden="true" class="h-full w-full opacity-60">
            <defs>
                <pattern id="grid-app" width="32" height="32" patternUnits="userSpaceOnUse">
                    <path d="M32 0H0V32" fill="none" stroke="#E3DFD5" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-app)"/>
            <g class="hidden md:block">
                <rect x="calc(100% - 320px)" y="96" width="32" height="32" fill="#E6ECF4"/>
                <rect x="calc(100% - 288px)" y="128" width="32" height="32" fill="#1F3A5F" opacity="0.8"/>
            </g>
        </svg>
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-paper to-transparent"></div>
    </div>

    <div class="relative z-10 flex-grow flex flex-col">
        <x-navigation :user="$user ?? null" />

        @if(!empty($announcement['enabled']) && !empty($announcement['text']))
            <div class="bg-paper border-b border-[#E3DFD5] px-4 py-2 text-center text-xs text-stone tracking-wide">
                <span>{{ $announcement['text'] }}</span>
            </div>
        @endif

        <main class="py-6 sm:py-8 flex-grow">
            {{ $slot }}
        </main>
    </div>

    <!-- Cohesive Minimal App Footer -->
    <footer class="relative z-10 border-t border-[#E3DFD5] text-xs text-stone py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full flex flex-col sm:flex-row items-center justify-between gap-3 bg-paper/80 backdrop-blur-xs">
        <div class="flex items-center space-x-2">
            <img src="/paxi-mark.svg" width="16" height="16" alt="" class="h-4 w-auto inline-block">
            <span class="font-bold text-accent">Paxi</span>
            <span>&copy; {{ date('Y') }} · IT Passport Study Companion</span>
        </div>

        <div class="flex items-center space-x-6 text-xs text-stone font-medium">
            <a href="{{ route('dashboard') }}" class="hover:text-ink transition-colors">Dashboard</a>
            <a href="{{ route('topics.index') }}" class="hover:text-ink transition-colors">Study</a>
            <a href="{{ route('assessments.index') }}" class="hover:text-ink transition-colors">Assessments</a>
            <a href="{{ route('analytics.index') }}" class="hover:text-ink transition-colors">Analytics</a>
        </div>

        <div class="inline-flex items-center space-x-1.5 bg-surface px-3 py-1 rounded-full border border-[#D9D5C9] text-xs font-mono">
            <span class="w-2 h-2 rounded-full bg-correct"></span>
            <span>IT Passport Reviewer</span>
        </div>
    </footer>

    @if(session('status'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('flashMessage', () => ({
                    show: true,
                    message: "{{ session('status') }}",
                    type: "success"
                }));
            });
        </script>
    @endif
    @livewireScripts
</body>
</html>
