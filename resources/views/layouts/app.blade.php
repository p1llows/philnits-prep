<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PhilNITS Prep') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        <x-navigation :user="$user ?? null" />

        <main class="py-10">
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
