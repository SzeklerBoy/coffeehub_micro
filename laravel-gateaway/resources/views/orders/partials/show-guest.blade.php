<x-guest-layout>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('notifications', {
                item: null,

                init() {
                    Echo.channel(`orders.{{ $order->uuid }}.status`)
                        .listen('OrderStatusChanged', (e) => {
                            if(navigator.vibrate) navigator.vibrate(300);
                            this.item = {
                                    title: `Order status updated to: ${e.order.status}`,
                                    link: `/orders/${e.order.uuid}`,
                                    created: new Date(e.order['created_at']).toLocaleTimeString().slice(0, -3),
                            };
                        });
                },
            });
        });
    </script>
    <div class="flex flex-col" x-data="{ itemToDelete: null }">
        <template x-data="$store.notifications" x-if="item">
            <div class="w-full grid gap-2.5 lg:grid-cols-2 mt-6">
                <a
                    class="flex gap-4 w-full px-2 py-1 border border-gray-300 bg-coffee-light-3 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:text-gray-100 dark:bg-coffee-dark-3 dark:border-coffee-dark-3 dark:hover:bg-coffee-dark-2 dark:hover:text-gray-200"
                    :href="item.link"
                >
                    <svg class="fill-coffee" width="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <title>Notification</title>
                        <path d="M19 17V11.8C18.5 11.9 18 12 17.5 12H17V18H7V11C7 8.2 9.2 6 12 6C12.1 4.7 12.7 3.6 13.5 2.7C13.2 2.3 12.6 2 12 2C10.9 2 10 2.9 10 4V4.3C7 5.2 5 7.9 5 11V17L3 19V20H21V19L19 17M10 21C10 22.1 10.9 23 12 23S14 22.1 14 21H10M21 6.5C21 8.4 19.4 10 17.5 10S14 8.4 14 6.5 15.6 3 17.5 3 21 4.6 21 6.5" />
                    </svg>
                    <div class="w-full">
                        <span class="float-end font-normal" x-text="item.created"></span>
                        <h6 x-text="item.title"></h6>
                        <p class="py-1 text-xs text-gray-600 dark:text-gray-400">
                            Tap to refresh order
                        </p>
                    </div>
                </a>
            </div>
        </template>
        <x-notification/>
        <div class="overflow-hidden bg-white border rounded-lg border-gray-300 dark:border-coffee-dark-3 dark:bg-coffee-dark-3">
            <div class="px-4 lg:px-6">
                <?php
                    $isPaid = $menuItems->every(fn($item) => $item->pivot->quantity <= $item->pivot->paid);
                ?>
                <div class="flex justify-between items-center">
                    <h2 class="my-4 text-xl font-semibold text-gray-600 dark:text-gray-300">
                        Order information:
                    </h2>
                </div>
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">
                    <p><b>Ordered at: </b>{{ $order->ordered_at }}</p>
                    @if($order->desk_id)
                        <p><b>Desk: </b>{{ $order->desk_id }}</p>
                    @else
                        <p><b>Group: </b>{{ $order->group_id }}</p>
                    @endif
                    <p><b>Total price: </b>{{ $menuItems->sum(fn ($item) => $item->price * $item->pivot->quantity) }}
                        lei</p>
                    <p><b>Will be served around: </b>{{ $orderWillBeCompletedAt}}</p>

                </h3>
                <div class="my-4">
                    <x-order-status :order="$order"/>
                    <x-payment-status :paid="$isPaid"/>
                </div>
                <div class="flex gap-x-3 float-right p-4 mb-3">
                    @if($isPaid || $order->status === 'served' || $order->status === 'completed')
                        <x-admin-button href="{{ route('orders.create') }}">
                            Back to the Menu
                        </x-admin-button>
                    @endif
                    @if(!$isPaid)
                        <x-admin-button href="{{ route('orders.checkout.all', $order) }}">
                            Pay order
                        </x-admin-button>
                    @endif
                </div>
            </div>
        </div>
        <div class="py-5">
            <h3 class="my-2 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Ordered items:
            </h3>
            <div class="grid lg:grid-cols-2 xl:grid-cols-3 w-full gap-5 whitespace-no-wrap">
                @foreach($menuItems as $menuItem)
                    <x-item-card title="{{ $menuItem->name }} ">
                        <x-slot:icon class="!p-0">
                            <img
                                class="object-cover w-full h-full"
                                src="{{ $menuItem['image_path'] ?? 'https://placehold.co/100x100?text=' . $menuItem->name[0] }}"
                                alt=""
                                loading="lazy"
                            />
                        </x-slot:icon>
                        <x-slot:actions>
                            @if($order->status === 'ordered' && $menuItem->pivot->paid === 0)
                                <button
                                    @click.prevent="itemToDelete = {{ $menuItem->id }}; $dispatch('open-modal', 'delete-item')"
                                    class="w-10 text-red-600 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <title>Delete item</title>
                                        <path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"/>
                                    </svg>
                                </button>
                            @endif
                        </x-slot:actions>
                        <div class="px-1">
                            <div class="flex justify-between gap-x-4 py-3 p-2">
                                <dt class="text-coffee-dark-0 dark:text-coffee-light-3">Price/item:</dt>
                                <dd class="flex items-start gap-x-2">
                                    {{ $menuItem->price }} lei
                                </dd>
                            </div>
                            <div class="flex justify-between gap-x-4 py-3 p-2">
                                <dt class="text-coffee-dark-0 dark:text-coffee-light-3">Quantity:</dt>
                                <dd class="flex items-start gap-x-2">
                                    {{ $menuItem->pivot->quantity }}
                                </dd>
                            </div>
                            <div class="flex justify-between gap-x-4 py-3 p-2">
                                <dt class="text-coffee-dark-0 dark:text-coffee-light-3">Total price:</dt>
                                <dd class="flex items-start gap-x-2">
                                    {{ $menuItem->pivot->quantity * $menuItem->price }} lei
                                </dd>
                            </div>
                        </div>
                    </x-item-card>
                @endforeach
            </div>
        </div>
        <x-modal name="delete-item">
            <div class="p-6">
                <h2 class="text-lg font-medium">
                    {{ __('Are you sure you want to delete this item?') }}
                </h2>
                <form method="POST" id="delete-item">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" x-on:click="deleteItem(itemToDelete)">
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
        <script>
            async function deleteItem(menuItemId) {
                let form = document.querySelector('#delete-item');
                form.setAttribute('action', `/orders/{{ $order->uuid }}/menuitem/${menuItemId}/delete`);
                form.submit();
            }
        </script>
    </div>
</x-guest-layout>
