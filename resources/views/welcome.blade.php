<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1F3A5F">
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|source-serif-4:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-2.5 font-bold text-xl text-accent tracking-tight">
            <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
            <span>{{ config('app.name', 'PhilNITS Prep') }}</span>
        </a>

        <div class="flex items-center space-x-3 text-sm font-medium">
            @if(Auth::check())
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-accent text-surface rounded-lg hover:bg-accent/90 transition-colors">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-stone hover:text-ink transition-colors">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-accent text-surface rounded-lg hover:bg-accent/90 transition-colors">
                    Register
                </a>
            @endif
        </div>
    </header>

    <!-- Main Hero -->
    <main class="max-w-3xl mx-auto px-6 py-16 text-center my-auto">
        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-accent bg-accent-tint px-3 py-1 rounded-full mb-6">
            IT Passport Certification
        </span>

        <h1 class="text-4xl sm:text-5xl font-bold text-ink tracking-tight leading-tight mb-6">
            Prepare for the PhilNITS Exam with Confidence
        </h1>

        <p class="text-stone text-base sm:text-lg mb-8 leading-relaxed max-w-xl mx-auto">
            Comprehensive practice questions, topic mastery reviews, and realistic timed assessment simulations.
        </p>

        <div class="flex items-center justify-center space-x-4">
            @if(Auth::check())
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-accent text-surface rounded-lg hover:bg-accent/90 text-sm font-medium transition-colors shadow-xs">
                    Go to Dashboard
                </a>
                <a href="{{ route('assessments.index') }}" class="px-6 py-3 border border-line bg-surface text-stone hover:text-ink hover:bg-paper rounded-lg text-sm font-medium transition-colors">
                    Take Assessment
                </a>
            @else
                <a href="{{ route('register') }}" class="px-6 py-3 bg-accent text-surface rounded-lg hover:bg-accent/90 text-sm font-medium transition-colors shadow-xs">
                    Get Started
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 border border-line bg-surface text-stone hover:text-ink hover:bg-paper rounded-lg text-sm font-medium transition-colors">
                    Log in
                </a>
            @endif
        </div>
    </main>

    <!-- Minimal Footer -->
    <footer class="py-6 text-center text-xs text-stone border-t border-line/40">
        &copy; {{ date('Y') }} {{ config('app.name', 'PhilNITS Prep') }}. All rights reserved.
    </footer>
</body>
</html>
