<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PhilNITS Prep') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-primary-700 mb-4">{{ config('app.name', 'PhilNITS Prep') }}</h1>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Prepare for the PhilNITS IP Passport Examination with confidence. Start your journey today.
            </p>
            <div class="space-x-4">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Register
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
