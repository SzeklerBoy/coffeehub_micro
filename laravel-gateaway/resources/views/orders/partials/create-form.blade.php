@props(['items', 'desk', 'group'])

<div x-data="cartComponent()" x-init="loadItems('coffee')">
    <div
        class="flex flex-wrap justify-between gap-2 md:gap-4 p-3 mt-2 mb-4 lg:mb-6 bg-gray-100 rounded-xl dark:bg-coffee-dark-3 dark:text-coffee-light-3">
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
        <div class="mx-auto grid">
            <ul class="flex flex-col gap-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <template x-for="item in items.data" :key="item.id">
                    <x-item-card
                        x-data="{ showQuantity: false, quantity: getCartQuantity(item.name), cartQuantity: getCartQuantity(item.name) }"
                        @update-quantity.window="quantity = getCartQuantity(item.name); cartQuantity = getCartQuantity(item.name)"
                        @click="showQuantity = !showQuantity; quantity = quantity || 1"
                        class="cursor-pointer"
                        canPopup
                    >
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
                            <div x-show="cartQuantity > 0"
                                 class="mr-1 h-10 text-coffee align-middle flex gap-1 justify-center items-center dark:text-coffee-light-3">
                                <span x-text="cartQuantity + ' x'" class="text-nowrap"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                     class="w-3/5 h-3/5 fill-current">
                                    <title>Cart</title>
                                    <path
                                        d="M5.5,21C4.72,21 4.04,20.55 3.71,19.9V19.9L1.1,10.44L1,10A1,1 0 0,1 2,9H6.58L11.18,2.43C11.36,2.17 11.66,2 12,2C12.34,2 12.65,2.17 12.83,2.44L17.42,9H22A1,1 0 0,1 23,10L22.96,10.29L20.29,19.9C19.96,20.55 19.28,21 18.5,21H5.5M12,4.74L9,9H15L12,4.74M12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13Z"/>
                                </svg>
                            </div>
                        </x-slot:actions>

                        <div class="h-full flex flex-col justify-between">
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
                            </div>

                            <div x-show="showQuantity" x-transition
                                 @click.stop
                                 class="p-2.5 cursor-default bg-coffee-light-3 border-t border-gray-900/5 flex justify-evenly dark:bg-coffee-dark-3">
                                <button @click.stop="quantity > 0 ? quantity-- : quantity"
                                        class="h-10 px-3 py-1 text-xl text-white bg-coffee hover:bg-coffee-hover rounded-full min-w-12">-
                                </button>
                                <span class="content-center text-lg font-semibold text-coffee-dark-0 dark:text-coffee-light-1"
                                      x-text="quantity"></span>
                                <button @click.stop="quantity < item.quantity ? quantity++ : quantity"
                                        class="h-10 px-3 py-1 text-xl text-white bg-coffee hover:bg-coffee-hover rounded-full min-w-12">+
                                </button>
                                <button
                                    @click.stop="updateCart(item.name, item.price, quantity), cartQuantity = quantity, showQuantity = false"
                                    class="h-10 px-3 py-1 text-white bg-coffee hover:bg-coffee-hover rounded-full">Add to cart
                                </button>
                            </div>
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

    <div
        class="w-full sticky left-0 bottom-4 lg:bottom-10 bg-white shadow-lg p-4 rounded-lg mt-6 z-20 dark:bg-coffee-dark-3 dark:text-white"
        x-show="cart.length > 0">
        <h2 class="text-xl font-bold mb-4">Cart Summary</h2>
        <ul class="max-h-52 overflow-y-auto">
            <template x-for="(item, index) in cart" :key="index">
                <li class="flex justify-between mb-2">
                    <span class="truncate" x-text="item.name + ' (' + item.quantity + 'x)'"></span>
                    <div class="flex-none align-middle">
                        <span class="align-middle" x-text=" item.quantity * item.price + ' lei'"></span>
                        <button class="ml-1 p-1 align-middle rounded hover:bg-gray-100 dark:hover:bg-gray-900" type="button" title="Remove from cart" @click="removeItem(index); $dispatch('update-quantity');">
                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
                        </button>
                    </div>
                </li>
            </template>
        </ul>
        <div class="flex items-center justify-between mt-4 font-bold">
            <span>Total:</span>
            <span
                x-text="cart.reduce((total, item) => total + (item.quantity * item.price), 0).toFixed(2) + ' lei'"></span>
            @if(request()->routeIs('orders.create'))
                <x-admin-button x-on:click.prevent="$dispatch('open-modal', 'code')">
                    Send Order
                </x-admin-button>
            @else
                <x-admin-button x-on:click.prevent="$dispatch('open-modal', 'confirm')"
                >
                    Send Order
                </x-admin-button>
            @endif
            <x-modal name="confirm">
                <div class="p-6">
                    <h2 class="text-lg font-medium">
                        {{ __('Are you sure you want to order?') }}
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-admin-button class="ms-3" x-on:click="submitOrder()">
                            {{ __('Send Order') }}
                        </x-admin-button>
                    </div>
                </div>
            </x-modal>

            <x-modal name="code">
                <div class="p-6">
                    <h2 class="text-lg font-medium mb-3">
                        {{ __('Enter the code to order') }}
                    </h2>
                    <x-input id="code-input" type="number" min="0" name="code" x-model="code"
                             x-on:keydown.enter="$dispatch('open-modal', 'confirm'); $dispatch('close-modal', 'code')"
                    >{{ __('Code: ') }}</x-input>
                    <div class="flex justify-end mt-5">
                        <x-admin-button
                            x-on:click.prevent="$dispatch('open-modal', 'confirm'); $dispatch('close-modal', 'code')"
                        >
                            {{ __('Submit') }}
                        </x-admin-button>
                    </div>
                </div>
            </x-modal>
        </div>
    </div>
</div>

<form method="POST"
      action="{{ $desk ? route('desks.orders.store', $desk) : ( $group ? route('groups.orders.store', $group) : route('orders.create')) }}"
      id="create-order" class="hidden" x-ref="orderForm">
    @csrf
    <input type="hidden" name="cart" x-ref="cartInput">
    <input type="hidden" name="code" x-ref="codeInput">
</form>


<script>
    function cartComponent() {
        return {
            cart: JSON.parse(sessionStorage.getItem('cart')) || [],
            code: '',
            activeCategory: '',
            loaded: false,
            items: {
                data: [],
                prev_page_url: null,
                next_page_url: null,
            },
            currentPage: 1,
            getCartQuantity(itemName) {
                const item = this.cart.find(item => item.name === itemName);
                return item ? item.quantity : 0;
            },
            updateCart(itemName, itemPrice, quantity) {
                if (quantity <= 0) {
                    this.cart = this.cart.filter(item => item.name !== itemName);
                } else {
                    const item = this.cart.find(item => item.name === itemName);
                    if (item) {
                        item.quantity = quantity;
                    } else {
                        this.cart.push({name: itemName, price: itemPrice, quantity: quantity});
                    }
                }
                this.saveCart();
            },
            removeItem(index) {
                this.cart.splice(index, 1);
                this.saveCart();
            },
            saveCart() {
                sessionStorage.setItem('cart', JSON.stringify(this.cart));
            },
            submitOrder() {
                console.log(this.$refs.cartInput, this.$refs.codeInput);
                this.$refs.cartInput.value = JSON.stringify(this.cart);
                this.$refs.codeInput.value = this.code || document.querySelector('#code-input').value;
                sessionStorage.removeItem('cart');
                this.$refs.orderForm.submit();
            },
            loadItems(category, page = 1) {
                this.activeCategory = category;
                this.loaded = false;
                fetch(`/menu-cat?category=${category}&page=${page}`, {
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
