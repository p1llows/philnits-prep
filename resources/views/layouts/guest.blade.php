<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1F3A5F">

    <title>{{ config('app.name', 'Passy') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased bg-paper min-h-screen flex flex-col justify-between selection:bg-accent-tint selection:text-accent relative overflow-x-hidden">
    <!-- Paper Grid Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <svg aria-hidden="true" class="h-full w-full opacity-60">
            <defs>
                <pattern id="grid-auth" width="32" height="32" patternUnits="userSpaceOnUse">
                    <path d="M32 0H0V32" fill="none" stroke="#E3DFD5" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-auth)"/>
        </svg>
    </div>

    <!-- Top Left Back to Home Link -->
    <div class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <a href="/" class="inline-flex items-center space-x-2 text-stone hover:text-accent font-medium text-xs sm:text-sm transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back to Passy</span>
        </a>
    </div>

    <!-- Main Auth Center Card -->
    <main class="relative z-10 flex-grow flex flex-col justify-center items-center px-4 py-8 sm:py-12">
        <div class="w-full sm:max-w-md space-y-6">
            <!-- Brand Logo Header -->
            <div class="text-center space-y-2">
                <a href="/" class="inline-flex items-center space-x-2.5 group" aria-label="Passy Home">
                    <x-mascot variant="avatar" :size="40" class="w-10 h-10 rounded-full shrink-0 shadow-xs transition-transform duration-200 group-hover:scale-105" />
                    <span class="font-extrabold text-accent text-3xl tracking-tight">Passy</span>
                </a>
                <p class="text-xs sm:text-sm text-stone">
                    IT Passport Examination Study Companion
                </p>
            </div>

            <!-- Card Container with Ambient Backglow -->
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-accent/15 via-accent/5 to-correct/15 rounded-3xl blur-lg opacity-75 pointer-events-none"></div>
                <div class="relative bg-surface border border-[#CFCABD] rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <!-- Simple Auth Footer -->
    <footer class="relative z-10 py-6 text-center text-xs text-stone border-t border-[#E3DFD5]/60 bg-paper/80 backdrop-blur-xs flex items-center justify-center space-x-2">
        <x-mascot variant="avatar" :size="16" class="w-4 h-4 rounded-full inline-block" />
        <span>&copy; {{ date('Y') }} Passy · Free for all examinees</span>
    </footer>
</body>
</html>

