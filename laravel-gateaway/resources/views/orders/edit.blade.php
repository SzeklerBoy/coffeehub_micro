<x-app-layout>
    <x-slot:title>Edit order #{{ $order->id }} status</x-slot:title>

    <div class="flex flex-col">
        <div class="overflow-hidden bg-white border border-gray-300 sm:rounded-lg dark:border-coffee-dark-3 dark:bg-coffee-dark-3">
            <?php
            $isPaid = $menuItems->every(fn($item) => $item->pivot->quantity <= $item->pivot->paid);
            ?>
            <div class="px-4 py-5 sm:px-6 text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">
                @if($order->desk_id)
                    <p><b>Desk: </b>{{ $order->desk_id }} ({{ $order->desk->description }})</p>
                @else
                    <p><b>Group: </b>{{ $order->group_id }} ({{ $order->group->description }})</p>
                @endif
                <p><b>Description: </b>{{ $order->description }}</p>
                <p><b>Ordered at: </b>{{ $order->ordered_at }}</p>
                <p><b>Total price: </b>{{ $menuItems->sum(fn ($item) => $item->price * $item->pivot->quantity) }} lei</p>
                <div class="mt-4 mb-4 text-base">
                    <x-order-status :order="$order"/>
                    <x-payment-status :paid="$isPaid"/>
                </div>
                <div class="mt-4 mb-4">
                    <form action="{{ route('orders.edit', $order->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select
                            class="h-10 font-semibold text-gray-700 bg-gray-200 border-0 rounded-md dark:hover:bg-coffee-dark-0 dark:bg-coffee-dark-1 dark:text-gray-200 focus:outline-none focus:ring-coffee dark:focus:ring-coffee-lighter"
                            name="status"
                        >
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                In progress
                            </option>
                            <option value="served" {{ $order->status === 'served' ? 'selected' : '' }}>
                                Served
                            </option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                        <x-admin-button
                            class="ml-2"
                            x-on:click.prevent="$dispatch('open-modal', 'confirm')"
                        >
                            Update Status
                        </x-admin-button>
                        <x-modal name="confirm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium">
                                    {{ __('Are you sure you want to update this order ?') }}
                                </h2>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-admin-button class="ms-3">
                                        {{ __('Update Order') }}
                                    </x-admin-button>
                                </div>
                            </div>
                        </x-modal>
                    </form>
                </div>
            </div>
        </div>
        <div class="py-5">
            <h3 class="my-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Ordered items
            </h3>
            <form id="order-items-form" method="POST" action="{{ route('orders.checkout', $order) }}">
                @csrf
                <div class="grid lg:grid-cols-2 xl:grid-cols-3 w-full gap-5 whitespace-no-wrap">
                    @foreach($menuItems as $menuItem)
                            <?php
                            if($menuItem->pivot->paid < $menuItem->pivot->quantity) $canPay = true
                            ?>
                        <x-item-card class="text-gray-700 dark:text-gray-400" :title="$menuItem->name">
                            <x-slot:icon class="!p-0">
                                <img
                                    class="object-cover w-full h-full"
                                    src="{{ $menuItem['image_path'] ?? 'https://placehold.co/100x100?text=' . $item->name[0] }}"
                                    alt=""
                                    loading="lazy"
                                />
                            </x-slot:icon>
                            <div class="py-2">
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Price/unit</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div class="font-medium text-gray-900 text-right dark:text-white">{{ $menuItem->price }} lei</div>
                                        </dd>
                                    </div>
                                </dl>
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Total Price</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div class="font-medium text-gray-900 text-right dark:text-white">{{ $menuItem->price * $menuItem->pivot->quantity }} lei</div>
                                        </dd>
                                    </div>
                                </dl>
                                <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                                    <div class="flex justify-between gap-x-4 py-3">
                                        <dt class="text-gray-500 dark:text-gray-300">Paid/All</dt>
                                        <dd class="flex items-start gap-x-2">
                                            <div class="font-medium text-gray-900 text-right dark:text-white">
                                                    <span class="{{ $menuItem->pivot->paid < $menuItem->pivot->quantity ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' }} px-2 py-1 font-semibold leading-tight rounded-full">
                                                        {{ $menuItem->pivot->paid }}/{{ $menuItem->pivot->quantity }}
                                                    </span>
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
    </div>
</x-app-layout>
