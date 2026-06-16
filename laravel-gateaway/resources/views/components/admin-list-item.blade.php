@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    ])

<li class="relative px-6 py-3">
    @if($active)
    <span
        class="absolute inset-y-0 left-0 w-1 bg-coffee dark:bg-coffee-light-1 rounded-tr-lg rounded-br-lg"
        aria-hidden="true"
    ></span>
    @endif
    <a
        class="inline-flex items-center w-full font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ $active ? 'text-gray-800 dark:text-gray-200' : 'dark:text-gray-400' }}"
        href="{{ $href }}"
    >
        <div class="w-5 mr-3">
            {{ $icon }}
        </div>
        {{ $slot }}
    </a>
</li>
