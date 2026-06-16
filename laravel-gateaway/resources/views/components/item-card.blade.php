@props(['title' => 'Title',
        'canPopup' => false])

<li
    {{ $attributes->merge(['class' => 'flex flex-col list-none overflow-hidden rounded-xl border border-gray-300 dark:border-coffee-dark-3 dark:text-gray-800']) }}>
    <div x-data="{ showPopup: false }"
        class="flex items-center gap-x-3 text-coffee border-b border-gray-900/5 bg-coffee-light-3 p-2.5 dark:bg-coffee-dark-3 dark:text-coffee-light-3">
        @isset($icon)
            <div {{ $icon->attributes->merge(['class' => 'h-10 w-10 text-gray-800 p-1 border rounded-md overflow-hidden bg-coffee-light-1 dark:bg-coffee-dark-2 dark:text-gray-200']) }}
                @click.stop="showPopup = true"
            >
                {{ $icon }}
            </div>
            @if($canPopup)
                <div
                    x-on:keydown.escape.window="showPopup = false"
                    x-show="showPopup"
                    x-cloak
                    class="fixed inset-0 top-24 overflow-y-auto px-4 py-6 sm:px-0 z-50"
                >
                    <div
                        x-show="showPopup"
                        class="fixed inset-0 transform transition-all"
                        x-on:click.stop="showPopup = false"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    >
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <div
                        x-show="showPopup"
                        class="mb-6 bg-white cursor-auto rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-fit sm:mx-auto dark:bg-coffee-dark-3 dark:text-white"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.stop
                    >
                        <div class="p-6 flex flex-col gap-6">
                            <div class="text-lg font-medium leading-6 truncate">{{ $title }}</div>
                            <div {{ $icon->attributes->merge(['class' => 'max-h-96 max-w-96 aspect-square text-gray-800 p-1 border rounded-md overflow-hidden bg-coffee-light-1 dark:bg-coffee-dark-2 dark:text-gray-200']) }}>
                                {{ $icon }}
                            </div>
                            <div class="flex justify-end">
                                <x-admin-button x-on:click.stop="showPopup = false">
                                    {{ __('Close') }}
                                </x-admin-button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endisset
        <div class="text-lg font-medium leading-6 truncate">{{ $title }}</div>
        @isset($actions)
            <div class="flex gap-1 ml-auto">
                {{ $actions }}
            </div>
        @endisset
    </div>
    <div class="h-full overflow-hidden bg-white dark:bg-coffee-dark-0 dark:text-coffee-light-3">
        {{ $slot }}
    </div>
</li>
