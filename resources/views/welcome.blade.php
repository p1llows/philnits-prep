<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1F3A5F">
    
    <title>Practice the IT Passport exam - {{ config('app.name', 'Paxi') }}</title>
    <meta name="description" content="Prepare for the IT Passport examination with Paxi: topic practice, mistake review, and timed assessments.">
    
    <!-- Favicon & PWA -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon-32.png" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="theme-color" content="#1F3A5F">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Fonts: Inter 400 & 500 only -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #study-loop, #faq {
            scroll-margin-top: 80px;
        }
    </style>
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-accent-tint selection:text-accent">
    <!-- Hero Section with Floating Navbar & Wide App Preview -->
    <x-welcome.hero />

    <!-- Study Loop Section -->
    <x-welcome.study-loop />

    <!-- Three Exam Fields Section -->
    <x-welcome.fields />

    <!-- FAQ Section -->
    <x-welcome.faq />

    <!-- Navy Closing Block -->
    <x-welcome.closing />

    <!-- Minimal Footer -->
    <x-welcome.footer />
</body>
</html>
