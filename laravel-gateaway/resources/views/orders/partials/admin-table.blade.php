<div class=" rounded-xl overflow-hidden border dark:border-coffee-dark-3">
    <table class="w-full whitespace-no-wrap">
        <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
        >
            <th class="px-4 py-3">
                <a href="{{ route('orders.index', ['sort_field' => 'id', 'sort_direction' => $sortField === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                    Order
                    @if($sortDirection === 'asc' && $sortField === 'id' && request()->has('sort_field'))
                        &#9650;
                    @elseif($sortDirection === 'desc' && $sortField === 'id')
                        &#9660;
                    @endif
                </a>
            </th>

            <th class="px-4 py-3">
                <a href="{{ route('orders.index', ['sort_field' => 'ordered_at', 'sort_direction' => $sortField === 'ordered_at' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                    Time
                    @if($sortDirection === 'asc' && $sortField === 'ordered_at')
                        &#9650;
                    @elseif($sortDirection === 'desc' && $sortField === 'ordered_at')
                        &#9660;
                    @endif
                </a>
            </th>
            <th class="px-4 py-3">
                <a href="{{ route('orders.index', ['sort_field' => 'status', 'sort_direction' => $sortField === 'status' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                    Status
                    @if($sortDirection === 'asc' && $sortField === 'status')
                        &#9650;
                    @elseif($sortDirection === 'desc' && $sortField === 'status')
                        &#9660;
                    @endif
                </a>
            </th>
            <th class="px-4 py-4">
                <a href="{{ route('orders.index', ['sort_field' => 'desk_id', 'sort_direction' => $sortField === 'desk_id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                    Desk / Group
                    @if($sortDirection === 'asc' && $sortField === 'desk_id')
                        &#9650;
                    @elseif($sortDirection === 'desc' && $sortField === 'desk_id')
                        &#9660;
                    @endif
                </a>
            </th>
            <th class="px-4 py-3">Time to prepare</th>
            <th class="px-4 py-3">Description</th>
            <th class="px-4 py-3">Actions</th>
        </tr>
        </thead>
        <tbody
            class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2"
        >
        @foreach($orders as $order)
            <tr class="text-gray-700 dark:text-gray-300">
                <td class="px-4 py-3 text-sm font-bold">
                    <div>
                        {{ $order->id }}
                    </div>
                </td>
                <td class="px-4 h-full my-3 text-sm line-clamp-2 overflow-clip align-middle">
                    {{ $order->ordered_at }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-order-status :order="$order"/>
                </td>
                <td class="px-4 py-3 text-sm">
                    @if($order->desk_id)
                        Desk: {{ $order->desk_id }}
                    @else
                        Group: {{ $order->group_id }}
                    @endif

                </td>
                <td class="px-4 py-3 text-sm">
                    {{ $order->totalPrepTime }} min
                </td>
                <td class="px-4 py-3 text-sm">
                    {{ $order->description }}
                </td>
                <td class="px-4 py-3">
                    <x-admin-button class="text-nowrap" href="{{ route('orders.show', $order) }}">
                        View Details
                    </x-admin-button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div
        class="min-h-12 py-1.5 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3"
    >
        {{ $orders->links() }}
    </div>
</div>
