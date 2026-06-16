@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-coffee focus:ring-coffee rounded-md shadow-sm dark:bg-coffee-dark-0 dark:text-white dark:border-gray-700 dark:ring-coffee-lighter']) !!}>
