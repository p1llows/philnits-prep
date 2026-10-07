<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1F3A5F">
    <title>{{ config('app.name', 'PhilNITS Prep') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-accent mb-4">{{ config('app.name', 'PhilNITS Prep') }}</h1>
            <p class="text-stone mb-8 max-w-md mx-auto">
                Prepare for the PhilNITS IP Passport Examination with confidence. Start your journey today.
            </p>
            <div class="space-x-4">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
                        Go to dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 border border-line text-stone hover:text-ink hover:bg-surface rounded-lg text-sm font-medium">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
                        Register
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
