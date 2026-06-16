@props(['order'])

@php
    $classes = [
        'ordered' => 'text-blue-500 bg-blue-100 dark:bg-blue-700 dark:text-blue-100',
        'cancelled' => 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100',
        'pending' => 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100',
        'served' => 'text-cyan-500 bg-cyan-100 dark:bg-cyan-500 dark:text-cyan-100',
        'completed' => 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100',
    ];

    $statusText = ucfirst($order->status);
@endphp

<a href="{{ Auth::check() ? route('orders.edit', $order) : '' }}" class="px-2 py-1 font-semibold leading-tight rounded-full {{ $classes[$order->status] }}" title="{{ Auth::check() ? 'Edit status' : 'Order status' }}">
    {{ $statusText }}
</a>
