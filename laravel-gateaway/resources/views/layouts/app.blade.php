@props(['showBackButton' => true, 'backURL' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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

        document.addEventListener('alpine:init', () => {
            Alpine.store('notifications', {
                queue: [],

                init() {
                    Echo.private(`orders.new`)
                        .listen('OrderCreated', (e) => {
                            if(navigator.vibrate) navigator.vibrate(300);
                            this.queue.push({
                                    title: e.order.desk_id !== null ? `New order at desk #${e.order['desk_id']}` : `New order at group #${e.order['group_id']}`,
                                    link: `/orders/${e.order.uuid}`,
                                    created: new Date(e.order['created_at']).toLocaleTimeString().slice(0, -3),
                                },
                            );
                        });
                },
            });
        });
    </script>
</head>
<body>
<div
    x-data="{ isSideMenuOpen: false, isNotificationsMenuOpen: false, isProfileMenuOpen: false }"
    class="flex h-dvh bg-coffee-light-1 dark:bg-coffee-dark-1"
    :class="{ 'overflow-hidden': isSideMenuOpen }"
>
    <!-- Desktop sidebar -->
    <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-coffee-darker md:block flex-shrink-0 drop-shadow"
    >
        <div class="pb-4 text-gray-500 dark:text-gray-400">
            <div class="h-16 shrink-0 flex items-center justify-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="inline-block h-12 w-auto"/>
                    @if(Auth::user()->getAttribute('role') === null)
                        <span class="text-xs align-bottom uppercase"> Admin </span>
                    @endif
                </a>
            </div>
            <ul class="mt-6">
                <x-admin-list-item href="{{ route('dashboard') }}" :active="request()->is('/')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Dashboard') }}
                </x-admin-list-item>
                <x-admin-list-item href="{{ route('desks.index') }}" :active="request()->is('desks*', 'groups*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 22H6A2 2 0 0 1 8 20V8H2V5H16V8H10V20A2 2 0 0 1 12 22M22 2V22H20V15H15V22H13V14A2 2 0 0 1 15 12H20V2Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Desks') }}
                </x-admin-list-item>
                <x-admin-list-item href="{{ route('menu.index') }}" :active="request()->is('menu*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M8.1,13.34L3.91,9.16C2.35,7.59 2.35,5.06 3.91,3.5L10.93,10.5L8.1,13.34M14.88,11.53L13.41,13L20.29,19.88L18.88,21.29L12,14.41L5.12,21.29L3.71,19.88L13.47,10.12C12.76,8.59 13.26,6.44 14.85,4.85C16.76,2.93 19.5,2.57 20.96,4.03C22.43,5.5 22.07,8.24 20.15,10.15C18.56,11.74 16.41,12.24 14.88,11.53Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Menu') }}
                </x-admin-list-item>
                @if(Auth::user()->getAttribute('role') === null)
                    <x-admin-list-item href="{{ route('profile.index') }}" :active="request()->is('staff*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M13.07 10.41A5 5 0 0 0 13.07 4.59A3.39 3.39 0 0 1 15 4A3.5 3.5 0 0 1 15 11A3.39 3.39 0 0 1 13.07 10.41M5.5 7.5A3.5 3.5 0 1 1 9 11A3.5 3.5 0 0 1 5.5 7.5M7.5 7.5A1.5 1.5 0 1 0 9 6A1.5 1.5 0 0 0 7.5 7.5M16 17V19H2V17S2 13 9 13 16 17 16 17M14 17C13.86 16.22 12.67 15 9 15S4.07 16.31 4 17M15.95 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13Z"/>
                            </svg>
                        </x-slot:icon>
                        {{ __('Staff') }}
                    </x-admin-list-item>
                    <x-admin-list-item href="{{route('reports.index')}}" :active="request()->is('reports*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    chart-arc</title>
                                <path
                                    d="M16.18,19.6L14.17,16.12C15.15,15.4 15.83,14.28 15.97,13H20C19.83,15.76 18.35,18.16 16.18,19.6M13,7.03V3C17.3,3.26 20.74,6.7 21,11H16.97C16.74,8.91 15.09,7.26 13,7.03M7,12.5C7,13.14 7.13,13.75 7.38,14.3L3.9,16.31C3.32,15.16 3,13.87 3,12.5C3,7.97 6.54,4.27 11,4V8.03C8.75,8.28 7,10.18 7,12.5M11.5,21C8.53,21 5.92,19.5 4.4,17.18L7.88,15.17C8.7,16.28 10,17 11.5,17C12.14,17 12.75,16.87 13.3,16.62L15.31,20.1C14.16,20.68 12.87,21 11.5,21Z"/>
                            </svg>
                        </x-slot:icon>
                        {{ __('Sales Reports') }}
                    </x-admin-list-item>
                @endif

                <x-admin-list-item href="{{ route('orders.index') }}" :active="request()->is('orders*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20.71,4.04C21.1,3.65 21.1,3 20.71,2.63L18.37,0.29C18,-0.1 17.35,-0.1 16.96,0.29L15,2.25L18.75,6M17.75,7L14,3.25L4,13.25V17H7.75L17.75,7Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Orders') }}
                </x-admin-list-item>
            </ul>
        </div>
    </aside>
    <!-- Mobile sidebar -->
    <!-- Backdrop -->
    <div
        x-cloak
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center md:hidden"
    ></div>
    <aside
        class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-coffee-darker md:hidden"
        x-cloak
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0 transform -translate-x-20"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform -translate-x-20"
        @click.outside="isSideMenuOpen = false"
        @keydown.escape="isSideMenuOpen = false"
    >
        <div class="pb-4 text-gray-500 dark:text-gray-400">
            <a
                class="flex justify-center font-bold text-gray-800 dark:text-gray-200"
                href="{{ route('dashboard') }}"
            >
                <x-application-logo class="h-14 mt-2"/>
            </a>
            <ul class="mt-6">
                <x-admin-list-item href="{{ route('dashboard') }}" :active="request()->is('/')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Dashboard') }}
                </x-admin-list-item>
                <x-admin-list-item href="{{ route('desks.index') }}" :active="request()->is('desks*', 'groups*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 22H6A2 2 0 0 1 8 20V8H2V5H16V8H10V20A2 2 0 0 1 12 22M22 2V22H20V15H15V22H13V14A2 2 0 0 1 15 12H20V2Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Desks') }}
                </x-admin-list-item>
                <x-admin-list-item href="{{ route('menu.index') }}" :active="request()->is('menu*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M8.1,13.34L3.91,9.16C2.35,7.59 2.35,5.06 3.91,3.5L10.93,10.5L8.1,13.34M14.88,11.53L13.41,13L20.29,19.88L18.88,21.29L12,14.41L5.12,21.29L3.71,19.88L13.47,10.12C12.76,8.59 13.26,6.44 14.85,4.85C16.76,2.93 19.5,2.57 20.96,4.03C22.43,5.5 22.07,8.24 20.15,10.15C18.56,11.74 16.41,12.24 14.88,11.53Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Menu') }}
                </x-admin-list-item>
                @if(Auth::user()->getAttribute('role') === null)
                    <x-admin-list-item href="{{ route('profile.index') }}" :active="request()->is('staff*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M13.07 10.41A5 5 0 0 0 13.07 4.59A3.39 3.39 0 0 1 15 4A3.5 3.5 0 0 1 15 11A3.39 3.39 0 0 1 13.07 10.41M5.5 7.5A3.5 3.5 0 1 1 9 11A3.5 3.5 0 0 1 5.5 7.5M7.5 7.5A1.5 1.5 0 1 0 9 6A1.5 1.5 0 0 0 7.5 7.5M16 17V19H2V17S2 13 9 13 16 17 16 17M14 17C13.86 16.22 12.67 15 9 15S4.07 16.31 4 17M15.95 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13Z"/>
                            </svg>
                        </x-slot:icon>
                        {{ __('Staff') }}
                    </x-admin-list-item>
                @endif

                <x-admin-list-item href="{{ route('orders.index') }}" :active="request()->is('orders*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20.71,4.04C21.1,3.65 21.1,3 20.71,2.63L18.37,0.29C18,-0.1 17.35,-0.1 16.96,0.29L15,2.25L18.75,6M17.75,7L14,3.25L4,13.25V17H7.75L17.75,7Z"/>
                        </svg>
                    </x-slot:icon>
                    {{ __('Orders') }}
                </x-admin-list-item>
            </ul>
        </div>
    </aside>
    <div class="flex flex-col flex-1 w-full">
        <header class="sticky top-0 z-10 py-4 bg-white shadow-md dark:bg-coffee-dark-3">
            <div
                class="container flex items-center justify-end h-full px-6 mx-auto text-coffee dark:text-coffee-light-3"
            >
                <!-- Mobile hamburger -->
                <button
                    class="p-1 mr-5 -ml-1 rounded-md self-start md:hidden focus:outline-none focus:ring-coffee"
                    @click="isSideMenuOpen = !isSideMenuOpen"
                    aria-label="Menu"
                >
                    <svg
                        class="w-6 h-6"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </button>

                <div class="flex-1"></div>

                <ul class="flex items-center flex-shrink-0 space-x-6 ">
                    <li class="relative" x-data="$store.themes">
                        <button
                            class="relative align-middle rounded-md focus:outline-none focus:ring-coffee"
                            @click="isThemeMenuOpen = !isThemeMenuOpen"
                            @click.outside="isThemeMenuOpen = false"
                            @keydown.escape="isThemeMenuOpen = false"
                            aria-label="Themes"
                            aria-haspopup="true"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>Themes</title><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z" /></svg>
                        </button>
                        <template x-if="isThemeMenuOpen">
                            <ul
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                @keydown.escape="isThemeMenuOpen = false"
                                class="absolute -right-12 md:right-0 top-8 w-52 p-2 space-y-1 text-gray-800 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-coffee-dark-0"
                            >
                                <h4 class="font-bold m-1">Color themes</h4>
                                <template x-for="item in themes">
                                    <li class="flex mt-1">
                                        <button
                                            class="flex gap-4 content-start items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-coffee-dark-3 dark:hover:text-gray-200"
                                            @click="setTheme(item.name)"
                                        >
                                            <x-coffee-bean width="28" ::fill="item.color"></x-coffee-bean>
                                            <div class="w-full text-left">
                                                <h6 x-text="item.name.charAt(0).toUpperCase() + item.name.slice(1)"></h6>
                                            </div>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </li>
                    <!-- Notifications menu -->
                    <li class="relative">
                        <button
                            class="relative align-middle rounded-md focus:outline-none focus:ring-coffee"
                            @click="isNotificationsMenuOpen = !isNotificationsMenuOpen"
                            @click.outside="isNotificationsMenuOpen = false"
                            @keydown.escape="isNotificationsMenuOpen = false"
                            aria-label="Notifications"
                            aria-haspopup="true"
                        >
                            <svg
                                class="w-6 h-6"
                                aria-hidden="true"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                                ></path>
                            </svg>
                            <!-- Notification badge -->
                            <span
                                aria-hidden="true"
                                x-cloak
                                x-show="$store.notifications.queue.length > 0"
                                class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full dark:border-gray-800"
                            ></span>
                        </button>
                        <template x-if="isNotificationsMenuOpen">
                            <ul
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                @keydown.escape="isNotificationsMenuOpen = false"
                                class="absolute -right-12 md:right-0 top-8 w-80 p-2 space-y-2 text-gray-800 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-coffee-dark-0"
                            >
                                <h4 class="m-1 font-bold">Notifications</h4>
                                <template x-if="$store.notifications.queue.length == 0">
                                    <p class="text-sm text-center p-2 pb-3 m-0 opacity-75">There are no notifications
                                        yet.</p>
                                </template>
                                <template x-for="item in $store.notifications.queue">
                                    <li class="flex">
                                        <a
                                            class="flex gap-4 w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-coffee-dark-3 dark:hover:text-gray-200"
                                            :href="item.link"
                                        >
                                            <svg width="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor">
                                                <path
                                                    d="M21 13.34C20.37 13.12 19.7 13 19 13V5H5V18.26L6 17.6L9 19.6L12 17.6L13.04 18.29C13 18.5 13 18.76 13 19C13 19.65 13.1 20.28 13.3 20.86L12 20L9 22L6 20L3 22V3H21V13.34M17 9V7H7V9H17M15 13V11H7V13H15M18 15V18H15V20H18V23H20V20H23V18H20V15H18Z"/>
                                            </svg>
                                            <div class="w-full">
                                                <span class="float-end font-normal" x-text="item.created"></span>
                                                <h6 x-text="item.title"></h6>
                                                <p class="py-1 text-xs text-gray-600 dark:text-gray-400">
                                                    Tap to view order
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </li>
                    <!-- Profile menu -->
                    <li class="relative">
                        <button
                            class="align-middle rounded-full focus:ring-coffee focus:outline-none"
                            @click="isProfileMenuOpen = !isProfileMenuOpen"
                            @click.outside="isProfileMenuOpen = false"
                            @keydown.escape="isProfileMenuOpen = false"
                            aria-label="Account"
                            aria-haspopup="true"
                        >
                            <img
                                class="object-cover w-8 h-8 rounded-full"
                                src="/img/avatar.jpg"
                                alt=""
                                aria-hidden="true"
                            />
                        </button>
                        <template x-if="isProfileMenuOpen">
                            <ul
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                @keydown.escape="isProfileMenuOpen = false"
                                class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-coffee-dark-0"
                                aria-label="submenu"
                            >
                                <li class="flex">
                                    <a
                                        class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-coffee-dark-3 dark:hover:text-gray-200"
                                        href="{{ route('profile.edit') }}"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-3"
                                            aria-hidden="true"
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            ></path>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="flex">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <a
                                            class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-coffee-dark-3 dark:hover:text-gray-200"
                                            href="{{route('logout')}}"
                                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            <svg
                                                class="w-4 h-4 mr-3"
                                                aria-hidden="true"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                                ></path>
                                            </svg>
                                            <span>Log out</span>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </div>
        </header>

        <main class="overflow-y-auto">
            <div class="container p-4 mx-auto grid md:p-6 2xl:p-8">
                @isset($title)
                    <div class="flex gap-3 flex-wrap justify-between">
                        <div class="flex space-x-3">
                            @if($showBackButton)
                                @if($backURL)
                                    <x-secondary-button class="h-10 w-10 !p-1 float-left" href="{{ $backURL }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>Back</title><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" /></svg>
                                          </x-secondary-button>
                                @else
                                    <x-secondary-button class="h-10 w-10 !p-1 float-left" onclick="history.back()">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>Back</title><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" /></svg>
                                    </x-secondary-button>
                                @endif
                            @endif
                            <h3 class="text-lg content-center font-semibold text-gray-600 dark:text-gray-300">
                                {{ $title }}
                            </h3>
                        </div>
                        <div class="flex space-x-3 md:space-x-5 items-center">
                            @isset($actions)
                                {{ $actions }}
                            @endisset
                        </div>
                    </div>
                    <x-notification/>
                @endisset
                {{$slot}}
            </div>
        </main>
    </div>
</div>
</body>
</html>
