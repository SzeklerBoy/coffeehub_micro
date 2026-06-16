<div>
    <ul class="flex flex-col lg:grid lg:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($orders as $order)
            <x-item-card title="Order #{{$order->id}}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M19.5 3.5L18 2L16.5 3.5L15 2L13.5 3.5L12 2L10.5 3.5L9 2L7.5 3.5L6 2L4.5 3.5L3 2V22L4.5 20.5L6 22L7.5 20.5L9 22L10.5 20.5L12 22L13.5 20.5L15 22L16.5 20.5L18 22L19.5 20.5L21 22V2L19.5 3.5M19 19H5V5H19V19M6 15H18V17H6M6 11H18V13H6M6 7H18V9H6V7Z"/>
                    </svg>
                </x-slot:icon>
                <x-slot:actions>
                    <a href="/orders/{{ $order->uuid  }}"
                       class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>Order
                                details</title>
                            <path
                                d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"/>
                        </svg>
                    </a>
                </x-slot:actions>
                <dl class="-my-3 px-4 py-3 text-sm leading-6 dark:divide-gray-700">
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-gray-500 dark:text-gray-300">Status:</dt>
                        <dd class="flex items-start gap-x-2">
                            <x-order-status :order="$order"/>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-gray-500 dark:text-gray-300">Desk / Group:</dt>
                        <dd class="flex items-start gap-x-2">
                            @if($order->desk_id)
                                Desk: {{ $order->desk_id }}
                            @else
                                Group: {{ $order->group_id }}
                            @endif

                        </dd>
                    </div>
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-gray-500 dark:text-gray-300">Created:</dt>
                        <dd class="flex items-start gap-x-2">
                            {{ $order->ordered_at }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-gray-500 dark:text-gray-300">Description:</dt>
                        <dd class="flex items-start gap-x-2">
                            {{ $order->description }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-x-4 py-3">
                        <dt class="text-gray-500 dark:text-gray-300">Time to prepare:</dt>
                        <dd class="flex items-start gap-x-2">
                            {{ $order->totalPrepTime }} min
                        </dd>
                    </div>

                </dl>
            </x-item-card>
        @endforeach
    </ul>
    <div
        class="mt-4 p-1 font-semibold text-gray-500 uppercase sm:grid-cols-9 dark:text-gray-400"
    >
        {{ $orders->links() }}
    </div>
</div>
