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
    </head>
    <body x-data="{ dark: false }" class="font-sans text-gray-900 antialiased bg-coffee-light-1 dark:bg-coffee-dark-1">
        <div class="h-dvh flex flex-col items-center pt-3">
            <div>
                <a href="/">
                    <x-application-logo class="h-14" />
                </a>
            </div>

            <div class="w-full p-4 pt-0 -mt-3">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
