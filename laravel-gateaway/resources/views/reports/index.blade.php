<x-app-layout :showBackButton="false">
    <x-slot:title>{{ __('Sales Reports') }}</x-slot:title>
    <div class="flex flex-col">
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <div
                class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-coffee-dark-3 overflow-hidden shadow sm:rounded-lg"
            >
                <div
                    class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </div>
                <div>
                    <p
                        class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Total Revenue
                    </p>
                    <p
                        class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                    >
                        {{ $totalRevenue }} lei
                    </p>
                </div>
            </div>
            <div
                class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-coffee-dark-3 overflow-hidden shadow sm:rounded-lg"
            >
                <div
                    class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                        ></path>
                    </svg>
                </div>
                <div>
                    <p
                        class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Total Orders
                    </p>
                    <p
                        class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                    >
                        {{ $ordersNumber }}
                    </p>
                </div>
            </div>
            <div
                class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-coffee-dark-3 overflow-hidden shadow sm:rounded-lg"
            >
                <div
                    class="p-3 mr-4 text-coffee bg-coffee-light-3 rounded-full dark:text-coffee dark:bg-coffee-light-3"
                >
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>invoice-list</title>
                        <path
                            d="M3 22V3H21V22L18 20L15 22L12 20L9 22L6 20L3 22M17 9V7H15V9H17M13 9V7H7V9H13M13 11H7V13H13V11M15 13H17V11H15V13Z"/>
                    </svg>
                </div>
                <div>
                    <p
                        class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Total Items Sold
                    </p>
                    <p
                        class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                    >
                        {{ $totalMenuItems }}
                    </p>
                </div>
            </div>
            <div
                class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-coffee-dark-3 overflow-hidden shadow sm:rounded-lg"
            >
                <div
                    class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                        ></path>
                    </svg>
                </div>
                <div>
                    <p
                        class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Staff members
                    </p>
                    <p
                        class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                    >
                        {{ $staffNumber }}
                    </p>
                </div>
            </div>
        </div>


        <div x-data="allOrders()" class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-coffee-dark-3 dark:text-gray-200">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h1 class="text-xl">All time orders </h1>
                <x-admin-button x-show="!showOrders" @click="showOrders = true , fetchOrders()">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Open</title>
                        <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/>
                    </svg>
                </x-admin-button>
                <x-admin-button x-show="showOrders" @click="showOrders = false">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Close</title>
                        <path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"/>
                    </svg>
                </x-admin-button>
            </div>
            <div x-show="showOrders" class="px-4 sm:px-6">
                <div x-show="!loaded">
                    <p class="text-lg text-gray-600 dark:text-gray-400">Loading...</p>
                </div>

                <div x-show="allOrders.data.length > 0" class="rounded-lg overflow-hidden border dark:border-coffee-dark-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-2"
                        >
                            <th class="px-4 py-3">
                                Order
                            </th>
                            <th class="px-4 py-3">
                                Time
                            </th>
                            <th class="px-4 py-3">
                                Status
                            </th>
                            <th class="px-4 py-4">
                                Desk / Group
                            </th>
                            <th class="px-4 py-3">Description</th>
                        </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-1"
                        >
                        <template x-for="order in allOrders.data" :key="order.id">
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm font-bold">
                                    <div x-text="order.id"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div x-text="(new Date(order.ordered_at)).toLocaleString()"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.status"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.desk_id"></div>
                                    <div x-text="order.group_id"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.description"></div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="p-2 flex justify-between bg-coffee-light-3 border-t dark:border-coffee-dark-3 dark:bg-coffee-dark-2">
                        <x-admin-button @click="prevPage"
                                        x-bind:disabled="!allOrders.prev_page_url"

                                        x-text="'Previous'">
                        </x-admin-button>
                        <x-admin-button @click="nextPage"
                                        x-bind:disabled="!allOrders.next_page_url"
                                        x-text="'Next'">
                        </x-admin-button>
                    </div>
                </div>
                <x-admin-button class="my-4 float-right" href="{{ route('reports.export') }}">
                    Export to CSV
                </x-admin-button>
            </div>
            <script>
                function allOrders() {
                    return {
                        showOrders: false,
                        allOrders: {
                            data: [],
                            prev_page_url: null,
                            next_page_url: null,
                        },
                        loaded: false,
                        currentPage: 1,
                        perPage: 10,

                        fetchOrders(page = 1) {
                            const url = new URL(`{{ route('allOrders') }}`);
                            url.searchParams.append('page', page);
                            url.searchParams.append('per_page', this.perPage);
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            fetch(url, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    this.allOrders = data;
                                    this.showOrders = true;
                                    this.loaded = true;
                                })
                                .catch(error => {
                                    console.error('Error loading orders:', error);
                                });
                        },

                        prevPage() {
                            if (this.allOrders.prev_page_url) {
                                this.fetchOrders(--this.currentPage);
                            }
                        },

                        nextPage() {
                            if (this.allOrders.next_page_url) {
                                this.fetchOrders(++this.currentPage);
                            }
                        }
                    }
                }
            </script>
        </div>
        <div x-data="ordersByDate()" class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-coffee-dark-3 dark:text-gray-200 mt-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h1 class="text-xl">Orders by date</h1>
                <x-admin-button x-show="!showOrdersByDate" @click="showOrdersByDate = true">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Open</title>
                        <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/>
                    </svg>
                </x-admin-button>
                <x-admin-button x-show="showOrdersByDate" @click="showOrdersByDate = false">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Close</title>
                        <path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"/>
                    </svg>
                </x-admin-button>
            </div>
            <div x-show="showOrdersByDate" class="px-4 pb-5 sm:px-6">
                <div class="flex gap-3">
                    <x-input type="date" id="startDate" name="startDate" class="border border-gray-300 rounded-md">Start date:</x-input>
                    <x-input type="date" id="endDate" name="endDate" class="border border-gray-300 rounded-md">End-date:</x-input>
                    <x-admin-button class="!self-end" @click="fetchOrdersByDate()">
                        Get Orders
                    </x-admin-button>
                </div>
                <div x-show="ordersDate.data.length > 0" class="mt-3 border rounded-xl overflow-hidden dark:border-coffee-dark-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-2">
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Time</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-4">Desk / Group</th>
                            <th class="px-4 py-3">Description</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-1">
                        <template x-for="order in ordersDate.data" :key="order.id">
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm font-bold">
                                    <div x-text="order.id"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div x-text="(new Date(order.ordered_at)).toLocaleString()"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.status"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.desk_id"></div>
                                    <div x-text="order.group_id"></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div x-text="order.description"></div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="p-2 flex justify-between bg-coffee-light-3 border-t dark:border-coffee-dark-3 dark:bg-coffee-dark-2">
                        <x-admin-button @click="prevPage"
                                        x-bind:disabled="!ordersDate.prev_page_url"
                                        x-text="'Previous'">
                        </x-admin-button>
                        <x-admin-button @click="nextPage"
                                        x-bind:disabled="!ordersDate.next_page_url"
                                        x-text="'Next'">
                        </x-admin-button>
                    </div>
                </div>
                <div x-show="ordersDate.data.length === 0 && loaded" class="px-4 py-5 sm:px-6">
                    <p class="text-lg text-red-600 dark:text-red-400 ">No orders found for the selected date range.</p>
                </div>
            </div>
            <script>
                function ordersByDate() {
                    return {
                        showOrdersByDate: false,
                        ordersDate: {
                            data: [],
                            prev_page_url: null,
                            next_page_url: null,
                        },
                        currentPage: 1,
                        perPage: 10,
                        loaded: false,
                        fetchOrdersByDate(page = 1) {
                            const startDate = document.getElementById('startDate').value;
                            const endDate = document.getElementById('endDate').value;
                            const url = new URL("{{ route('ordersByDate') }}");
                            url.searchParams.append('start_date', startDate);
                            url.searchParams.append('end_date', endDate);
                            url.searchParams.append('page', page);
                            url.searchParams.append('per_page', this.perPage);
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            fetch(url, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    this.ordersDate = data;
                                    this.showOrdersByDate = true;
                                    this.loaded = true;
                                })
                                .catch(error => {
                                    console.error('Error loading orders by date:', error);
                                });
                        },
                        prevPage() {
                            if (this.ordersDate.prev_page_url) {
                                this.fetchOrdersByDate(--this.currentPage);
                            }
                        },

                        nextPage() {
                            if (this.ordersDate.next_page_url) {
                                this.fetchOrdersByDate(++this.currentPage);
                            }
                        }
                    }
                }
            </script>
        </div>
        <div x-data="mostSoldItems()" class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-coffee-dark-3 dark:text-gray-200 mt-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h1 class="text-xl">Most popular items</h1>
                <x-admin-button x-show="!showMSI" @click="showMSI = true; fetchMostSoldItems()">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Open</title>
                        <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/>
                    </svg>
                </x-admin-button>
                <x-admin-button x-show="showMSI" @click="showMSI = false">
                    <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Close</title>
                        <path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"/>
                    </svg>
                </x-admin-button>
            </div>
            <div x-show="showMSI" class="px-4 py-5 sm:px-6">
                <div x-show="!loaded" class="px-4 py-5 sm:px-6">
                    <p class="text-lg text-gray-600 dark:text-gray-400">Loading most popular items, please wait...</p>
                </div>

                <div x-show="loaded" class="px-4 py-5 sm:px-6">
                    <canvas id="mostSoldItemsChart" width="200" height="100"></canvas>
                    <div x-show="mostSoldItems.length > 0" class="mt-4">
                        <template x-for="item in mostSoldItems" :key="item.name">
                            <div class="flex justify-between">
                                <p x-text="item.name"></p>
                                <p x-text="item.quantity"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                function mostSoldItems() {
                    return {
                        showMSI: false,
                        mostSoldItems: [],
                        loaded: false,
                        fetchMostSoldItems() {
                            const url = new URL("{{ route('mostSoldItems') }}");
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            fetch(url, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Data received:', data); // Add this line to check the format
                                    this.mostSoldItems = data;
                                    this.loaded = true;

                                    const labels = data.map(item => item.name);
                                    const quantities = data.map(item => item.quantity);

                                    const ctx = document.getElementById('mostSoldItemsChart').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Quantity Sold',
                                                data: quantities,
                                                backgroundColor: '#fffefb',
                                                borderColor: '#6a450b',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                })
                                .catch(error => {
                                    console.error('Error loading most sold items:', error);
                                });
                        }
                    }
                }
            </script>
        </div>

    </div>
</x-app-layout>
