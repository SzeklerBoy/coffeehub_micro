@props([
    'paid' => false,
])

<td>
    @if($paid)
        <span
            class="px-2 py-1 ml-2 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
            Paid
        </span>
    @else
        <span
            class="px-2 py-1 ml-2 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
            Not paid
        </span>
    @endif
</td>
