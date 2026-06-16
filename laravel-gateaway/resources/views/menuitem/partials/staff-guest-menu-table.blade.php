<div x-data="cartComponent()" x-init="loadItems('coffee')">
    <div
        class="flex flex-wrap justify-between gap-2 md:gap-4 p-3 mb-4 bg-gray-100 rounded-xl dark:bg-coffee-dark-3 dark:text-coffee-light-3">
        @foreach ($categories as $category)
            <button class="flex-1 md:flex-none text-center py-2 px-4 md:w-auto"
                    x-on:click="loadItems('{{ $category }}')"
                    :class="{ 'bg-coffee text-white rounded-lg': activeCategory === '{{ $category }}' }">
                {{ ucfirst($category) }}
            </button>
        @endforeach
    </div>
    <x-notification/>
    <div x-show="!loaded"
         class="flex items-center gap-x-4 text-coffee border-b border-gray-900/5 rounded-lg bg-coffee-light-3 p-3 dark:bg-coffee-dark-3 dark:text-coffee-light-3">
        <span> Loading...</span>
    </div>
    <div x-show="loaded" class="flex flex-1 w-full">
        <div class="w-full mx-auto grid">
            <ul class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <template x-for="item in items.data" :key="item.id">
                    <x-item-card can-popup>
                        <x-slot:title>
                            <span x-text="item.name"></span>
                        </x-slot:title>
                        <x-slot:icon class="!p-0">
                            <img
                                class="object-cover w-full h-full cursor-pointer"
                                x-bind:src="item.image_path ? item.image_path : 'https://placehold.co/100x100?text=' + item.name[0]"
                                alt=""
                                loading="lazy"
                            />
                        </x-slot:icon>
                        <x-slot:actions>
                                <span x-show="item.quantity === 0"
                                      class="px-2 py-1 font-semibold text-nowrap leading-tight border border-red-500 text-red-700 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100 dark:border-none">
                                    Out of stock
                                </span>
                        </x-slot:actions>
                        <div class="py-2">
                            <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                <div class="flex justify-between gap-x-4 py-3">
                                    <dt class="text-gray-500 dark:text-coffee-light-3">Description</dt>
                                    <dd class="flex items-start gap-x-2">
                                        <div class="font-medium text-gray-900 text-right dark:text-coffee-light-3"
                                             x-text="item.description"></div>
                                    </dd>
                                </div>
                            </dl>
                            <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                <div class="flex justify-between gap-x-4 py-3">
                                    <dt class="text-gray-500 dark:text-coffee-light-3">Price</dt>
                                    <dd class="flex items-start gap-x-2">
                                        <div class="font-medium text-gray-900 text-right dark:text-coffee-light-3"
                                             x-text="item.price + ' lei'"></div>
                                    </dd>
                                </div>
                            </dl>

                            <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                <div class="flex justify-between gap-x-4 py-3">
                                    <dt class="text-gray-500 dark:text-coffee-light-3">Category</dt>
                                    <dd class="flex items-start gap-x-2">
                                        <div class="font-medium text-gray-900 text-right dark:text-coffee-light-3"
                                             x-text="item.category.charAt(0).toUpperCase() + item.category.slice(1)"></div>
                                    </dd>
                                </div>
                            </dl>

                            <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                <div class="flex justify-between gap-x-4 py-3">
                                    <dt class="text-gray-500 dark:text-coffee-light-3">Time to prepare</dt>
                                    <dd class="flex items-start gap-x-2">
                                        <div class="font-medium text-gray-900 text-right dark:text-coffee-light-3"
                                             x-text="item.ETAinMinutes + ' min'"></div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </x-item-card>
                </template>
            </ul>

            <div
                x-show="items.prev_page_url || items.next_page_url"
                class="mt-4 flex justify-between">
                <x-admin-button @click="prevPage"
                                x-bind:disabled="!items.prev_page_url"
                                x-text="'Previous'">
                </x-admin-button>
                <x-admin-button @click="nextPage"
                                x-bind:disabled="!items.next_page_url"
                                x-text="'Next'">
                </x-admin-button>
            </div>

        </div>
    </div>

</div>

<script>
    function cartComponent() {
        return {
            activeCategory: '',
            loaded: false,
            items: {
                data: [],
                prev_page_url: null,
                next_page_url: null,
            },
            currentPage: 1,
            loadItems(category, page = 1) {
                this.activeCategory = category;
                this.loaded = false;

                let url = `/menu-cat?category=${category}&page=${page}`;
                let search = document.querySelector('#menu-search').value;
                if(search) {
                    url = `${url}&search=${search}`;
                }

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.items.data = data.data;
                        this.items.prev_page_url = data.prev_page_url;
                        this.items.next_page_url = data.next_page_url;
                        this.loaded = true;
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
            },
            prevPage() {
                if (this.items.prev_page_url) {
                    this.loadItems(this.activeCategory, --this.currentPage);
                }
            },

            nextPage() {
                if (this.items.next_page_url) {
                    this.loadItems(this.activeCategory, ++this.currentPage);
                }
            }
        };
    }

</script>
