<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1F3A5F">

    <title>{{ config('app.name', 'PhilNITS Prep') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500|source-serif-4:400,500&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-paper text-ink">
    <div class="min-h-screen bg-paper">
        <x-navigation :user="$user ?? null" />

        <main class="py-8">
            {{ $slot }}
        </main>
    </div>

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
</body>
</html>
