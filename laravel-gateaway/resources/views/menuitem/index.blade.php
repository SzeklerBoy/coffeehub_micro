<x-app-layout :showBackButton="false">
    <x-slot:title>Menu items</x-slot:title>
    <x-slot:actions>
        <div class="relative focus-within:text-coffee-500">
            <div class="absolute inset-y-0 flex items-center pl-2 dark:text-gray-300">
                <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
            </div>
            <form action="{{ route('menu.index') }}" method="GET" class="w-full flex space-x-3 md:space-x-5">
                <input
                    id="menu-search"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-52 pl-8 pr-2 text-sm text-ellipsis text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-coffee-dark-0 dark:border dark:text-gray-200 dark:placeholder-gray-300 focus:placeholder-gray-500 focus:bg-white dark:focus:bg-black focus:ring-coffee focus:shadow-outline-coffee form-input"
                    type="search"
                    placeholder="Search for menu item"
                    aria-label="Search"
                />
                <x-admin-button type="submit">
                    Search
                </x-admin-button>
                @if(request()->has('search'))
                    <x-admin-button href="{{ route('menu.index') }}" class="hidden sm:block">
                        Clear
                    </x-admin-button>
                    <x-admin-button href="{{ route('menu.index') }}" class="sm:hidden !px-2">
                        <svg height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="currentColor"><title>Reset search</title>
                            <path
                                d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                        </svg>
                    </x-admin-button>
                @endif
            </form>
        </div>
        @if(Auth::user()->getAttribute('role') === null)
            <x-admin-button href="{{ route('menu.create') }}">
                Add item
            </x-admin-button>
        @endif
    </x-slot:actions>

    @if(Auth::user()->getAttribute('role') === null)
        @include('menuitem.partials.admin-menu-table')
        <div class="flex justify-end mt-4">
            <x-admin-button href="{{route('menu.export') }}">
                Export to CSV
            </x-admin-button>
            <x-admin-button class="ml-4" x-on:click.prevent="$dispatch('open-modal', 'import')">
                Import from CSV
            </x-admin-button>
        </div>
        <x-modal name="import">
            <div class="p-6">
                <h2 class="text-lg font-medium">
                    {{ __('Import menu items from CSV') }}
                </h2>
                <form method="POST" action="{{ route('menu.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="mt-4" required>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-admin-button class="ms-3" name="action" value="update">
                            {{ __('Update existing menu') }}
                        </x-admin-button>
                    </div>
                </form>
            </div>
        </x-modal>

    @else
        @include('menuitem.partials.staff-guest-menu-table')
    @endif

</x-app-layout>
