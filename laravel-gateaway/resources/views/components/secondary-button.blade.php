@if($attributes->has('href'))
    <a {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center h-min px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-coffee tracking-widest shadow-sm hover:bg-coffee-light-1 focus:outline-none focus:ring-2 focus:ring-coffee active:bg-coffee-light-3 dark:active:bg-coffee-dark-1 dark:bg-coffee-dark-3 dark:border-coffee-dark-3 dark:text-white dark:hover:bg-coffee-dark-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center h-min px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-coffee tracking-widest shadow-sm hover:bg-coffee-light-1 focus:outline-none focus:ring-2 focus:ring-coffee active:bg-coffee-light-3 dark:active:bg-coffee-dark-1 dark:bg-coffee-dark-3 dark:border-coffee-dark-3 dark:text-white dark:hover:bg-coffee-dark-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </button>
@endif
