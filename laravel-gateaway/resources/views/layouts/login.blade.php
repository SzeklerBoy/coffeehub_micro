<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="icon" href="/favicon-32.png" sizes="35x32">
    <link rel="icon" href="/favicon-64.png" sizes="64x64">
    <link rel="icon" href="/favicon-96.png" sizes="96x96">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        let theme = localStorage.getItem('theme');
        if(theme) {
            document.querySelector('html').setAttribute('data-theme', theme);
        }
    </script>
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-coffee-light-2 dark:bg-coffee-dark-3">
    <div>
        <a href="/">
            <x-application-logo class="h-20" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-coffee-dark-1">
        {{ $slot }}
    </div>
</div>
</body>
</html>
