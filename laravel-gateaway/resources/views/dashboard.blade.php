<x-app-layout>
    <div class="text-gray-700 grid gap-8 my-4 md:grid-cols-2 dark:text-gray-300">
        <a href="{{ route('desks.index') }}"
           class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
        >
            <svg
                class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>table-chair</title>
                <path
                    d="M12 22H6A2 2 0 0 1 8 20V8H2V5H16V8H10V20A2 2 0 0 1 12 22M22 2V22H20V15H15V22H13V14A2 2 0 0 1 15 12H20V2Z"/>
            </svg>
            <div class="p-4">
                <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                    Desks
                </h4>
                <p>
                    Here you can view, create and merge desks.
                </p>
            </div>
        </a>
        <a href="{{ route('menu.index') }}"
           class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
        >
            <svg
                class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>silverware</title>
                <path
                    d="M8.1,13.34L3.91,9.16C2.35,7.59 2.35,5.06 3.91,3.5L10.93,10.5L8.1,13.34M14.88,11.53L13.41,13L20.29,19.88L18.88,21.29L12,14.41L5.12,21.29L3.71,19.88L13.47,10.12C12.76,8.59 13.26,6.44 14.85,4.85C16.76,2.93 19.5,2.57 20.96,4.03C22.43,5.5 22.07,8.24 20.15,10.15C18.56,11.74 16.41,12.24 14.88,11.53Z"/>
            </svg>
            <div class="p-4">
                <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                    Menu
                </h4>
                <p>
                    Here you can edit the menu. You can create, edit, update or delete menu items.
                </p>
            </div>
        </a>
        <a href="{{ route('orders.index') }}"
           class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
        >
            <svg
                class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                    border-color</title>
                <path
                    d="M20.71,4.04C21.1,3.65 21.1,3 20.71,2.63L18.37,0.29C18,-0.1 17.35,-0.1 16.96,0.29L15,2.25L18.75,6M17.75,7L14,3.25L4,13.25V17H7.75L17.75,7Z"/>
            </svg>
            <div class="p-4">
                <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                    Orders
                </h4>
                <p>
                    Here you can manage the orders. You can view and update orders.
                </p>
            </div>
        </a>
        @if(Auth::user()->getAttribute('role') === null)
            <a href="{{ route('profile.index') }}"
               class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <svg
                    class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                        account-group</title>
                    <path
                        d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                </svg>
                <div class="p-4">
                    <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                        Staff
                    </h4>
                    <p>
                        Here you can manage the staff. You can view, create or delete staff member profiles.
                    </p>
                </div>
            </a>
            <a href="{{route('reports.index')}}"
               class="flex items-center min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <svg
                    class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                        chart-arc</title>
                    <path
                        d="M16.18,19.6L14.17,16.12C15.15,15.4 15.83,14.28 15.97,13H20C19.83,15.76 18.35,18.16 16.18,19.6M13,7.03V3C17.3,3.26 20.74,6.7 21,11H16.97C16.74,8.91 15.09,7.26 13,7.03M7,12.5C7,13.14 7.13,13.75 7.38,14.3L3.9,16.31C3.32,15.16 3,13.87 3,12.5C3,7.97 6.54,4.27 11,4V8.03C8.75,8.28 7,10.18 7,12.5M11.5,21C8.53,21 5.92,19.5 4.4,17.18L7.88,15.17C8.7,16.28 10,17 11.5,17C12.14,17 12.75,16.87 13.3,16.62L15.31,20.1C14.16,20.68 12.87,21 11.5,21Z"/>
                </svg>
                <div class="p-4">
                    <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                        Sales Reports
                    </h4>
                    <p>
                        Here you view the sales reports. You can filter by date and export the data.
                    </p>
                </div>
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();
                        this.closest('form').submit();"
               class="flex items-center h-full min-w-0 bg-white border border-gray-300 hover:bg-coffee-light-3 hover:text-black rounded-lg overflow-hidden shadow-xs dark:bg-coffee-dark-2 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-3 dark:hover:text-white"
            >
                <svg
                    class="w-20 h-full text-coffee p-6 flex-none bg-coffee-light-3 dark:text-coffee-light-3 dark:bg-coffee-dark-3"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <title>Logout</title>
                    <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12M4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z" />
                </svg>
                <div class="p-4">
                    <h4 class="mb-4 font-semibold text-coffee dark:text-coffee-light-3">
                        Log out
                    </h4>
                    <p>
                        Here you can log out if you leave the cafe.
                    </p>
                </div>
            </a>
        </form>
    </div>
</x-app-layout>
