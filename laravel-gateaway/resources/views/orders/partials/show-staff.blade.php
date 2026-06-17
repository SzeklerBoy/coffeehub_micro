<x-app-layout :backURL="route('orders.index')">
    <x-slot:title>Order #{{ $order->id }} Information</x-slot:title>
    <x-slot:actions>
        @auth
            <div>
                <x-admin-button href="/orders/{{$order->uuid}}/edit">
                    Edit order
                </x-admin-button>
                <x-admin-button class="ml-4" x-on:click.prevent="$dispatch('open-modal', 'delete-order')">
                    Delete order
                </x-admin-button>
            </div>
        @endauth
    </x-slot:actions>
    <div class="flex flex-col" x-data="{ itemToDelete: null }">
        <?php
            $isPaid = $menuItems->every(fn($item) => $item->pivot->quantity <= $item->pivot->paid);
        ?>
        <div class="overflow-hidden bg-white border border-gray-300 rounded-lg dark:border-coffee-dark-3 dark:bg-coffee-dark-3">
            <div class="px-4 py-5 sm:px-6 text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">
                @if($order->desk_id)
                    <p><b>Desk: </b>{{ $order->desk_id }} ({{ $order->desk->description }})</p>
                @else
                    <p><b>Group: </b>{{ $order->group_id }} ({{ $order->group->description }})</p>
                @endif
                <p><b>Description: </b>{{ $order->description }}</p>
                <p><b>Ordered at: </b>{{ $order->ordered_at }}</p>
                <p><b>Total price: </b>{{ $menuItems->sum(fn ($item) => $item->price * $item->pivot->quantity) }} lei</p>
                @if(in_array($order->status, ['ordered', 'pending']))
                    <p><b>Time to prepare: </b>{{ $order->totalPrepTime }} min </p>
                    <p><b>Will be served around: </b>{{ $orderWillBeCompletedAt}}</p>
                @endif
                <div class="mt-4 mb-4 text-base">
                    <x-order-status :order="$order"/>
                    <x-payment-status :paid="$isPaid"/>
                </div>
            </div>
        </div>
        <div class="py-5">
            @unless($isPaid)
                <div class="float-right flex justify-end gap-3 mt-3">
                    <x-admin-button href="{{ route('orders.checkout.all', $order) }}">
                        Pay All
                    </x-admin-button>
                    <x-admin-button form="order-items-form">
                        Pay Selection
                    </x-admin-button>
                </div>
            @endunless
            <h3 class="my-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Ordered items
            </h3>
            <form id="order-items-form" method="POST" action="{{ route('orders.checkout', $order) }}">
                @csrf
                <div class="grid lg:grid-cols-2 xl:grid-cols-3 w-full gap-5 whitespace-no-wrap">
                    @foreach($menuItems as $menuItem)
                            <?php
                                if ($menuItem->pivot->paid < $menuItem->pivot->quantity) $canPay = true
                            ?>
                        <x-item-card class="text-gray-700 dark:text-gray-400" :title="$menuItem->name">
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
                                        type="button"
                                        @click="itemToDelete = {{ $menuItem->id }}; $dispatch('open-modal', 'delete-item')"
                                        class="w-10 text-red-600 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1 dark:text-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <title>Delete item</title>
                                            <path
                                                d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"/>
                                        </svg>
                                    </button>
                                @endif
                            </x-slot:actions>
                            <div class="py-2">
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Price/unit</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div
                                                class="font-medium text-gray-900 text-right dark:text-white">{{ $menuItem->price }}
                                                lei
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Total Price</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div
                                                class="font-medium text-gray-900 text-right dark:text-white">{{ $menuItem->price * $menuItem->pivot->quantity }}
                                                lei
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Paid/All</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div class="font-medium text-gray-900 text-right dark:text-white">
                                                    <span
                                                        class="{{ $menuItem->pivot->paid < $menuItem->pivot->quantity ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' }} px-2 py-1 font-semibold leading-tight rounded-full">
                                                        {{ $menuItem->pivot->paid }}/{{ $menuItem->pivot->quantity }}
                                                    </span>
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Pay</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div
                                                x-data="{ quantity: 0, max: {{ $menuItem->pivot->quantity - $menuItem->pivot->paid }} }"
                                                class="font-medium text-gray-900 text-right dark:text-white">
                                                @if($menuItem->pivot->paid < $menuItem->pivot->quantity)
                                                    <div class="flex gap-1">
                                                        <x-secondary-button
                                                            @click="quantity > 0 ? quantity-- : quantity"
                                                            type="button"
                                                            class="px-3 py-0 !h-8 text-xl">
                                                            -
                                                        </x-secondary-button>
                                                        <x-input
                                                            class="self-center !mt-0 h-8 text-center !border-coffee dark:text-white"
                                                            name="items[{{ $loop->index }}][quantity]"
                                                            type="number"
                                                            min="0"
                                                            max="{{ $menuItem->pivot->quantity - $menuItem->pivot->paid }}"
                                                            ::value="quantity"
                                                        >
                                                        </x-input>
                                                        <input type="hidden"
                                                               name="items[{{ $loop->index }}][menu_item_id]"
                                                               value="{{ $menuItem->id }}">
                                                        <x-secondary-button
                                                            @click="quantity < max ? quantity++ : quantity"
                                                            type="button"
                                                            class="px-3 py-0 !h-8 text-xl">
                                                            +
                                                        </x-secondary-button>
                                                    </div>
                                                @else
                                                    All paid
                                                @endif
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </x-item-card>
                    @endforeach
                </div>
            </form>
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
    </div>

    <script>
        async function deleteItem(menuItemId) {
            let form = document.querySelector('#delete-item');
            form.setAttribute('action', `/orders/{{ $order->uuid }}/menu/${menuItemId}/delete`);
            form.submit();
        }
    </script>

    <x-modal name="delete-order">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete this order? This action cannot be undone.') }}
            </h2>
            <form method="POST" action="{{ route('orders.destroy', $order) }}" id="delete-order" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" form="delete-order">
                    {{ __('Delete Order') }}
                </x-danger-button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
