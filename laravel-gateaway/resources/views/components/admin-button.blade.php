@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => 'px-4 py-2 text-sm h-min self-center font-medium leading-5 text-white transition-colors duration-150 bg-coffee border border-transparent rounded-lg active:bg-coffee-lighter hover:bg-coffee-lighter focus:outline-none focus:shadow-outline-coffee transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 text-sm h-min self-center font-medium leading-5 text-white transition-colors duration-150 bg-coffee border border-transparent rounded-lg active:bg-coffee-lighter hover:bg-coffee-lighter focus:outline-none focus:shadow-outline-coffee transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </button>
@endif
