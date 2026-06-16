@props(['desks', 'groups'])

<div>
    <ul class="flex flex-col lg:grid lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($desks as $desk)
            @if($desk->joined_at)
                @continue
            @endif
            <x-item-card title="Desk #{{ $desk->id }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                            table-chair</title>
                        <path
                            d="M12 22H6A2 2 0 0 1 8 20V8H2V5H16V8H10V20A2 2 0 0 1 12 22M22 2V22H20V15H15V22H13V14A2 2 0 0 1 15 12H20V2Z"/>
                    </svg>
                </x-slot:icon>
                <x-slot:actions>
                    <a href="{{ route('desks.orders.create', $desk) }}"
                       class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                Create order</title>
                            <path
                                d="M18 15V18H15V20H18V23H20V20H23V18H20V15H18M13.26 20.74L12 22L10.5 20.5L9 22L7.5 20.5L6 22L4.5 20.5L3 22V2L4.5 3.5L6 2L7.5 3.5L9 2L10.5 3.5L12 2L13.5 3.5L15 2L16.5 3.5L18 2L19.5 3.5L21 2V13.35C20.37 13.13 19.7 13 19 13V5H5V19H13C13 19.57 13.1 20.22 13.26 20.74M14.54 15C14 15.58 13.61 16.25 13.35 17H6V15H14.54M6 11H18V13H6V11M6 7H18V9H6V7Z"/>
                        </svg>
                    </a>
                    @if($desk->is_occupied && $desk->latestOrder)
                        <a href="{{ route('desks.orders.index', $desk) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>View orders</title><path d="M15 16.69V13H16.5V15.82L18.94 17.23L18.19 18.53L15 16.69M10.58 20.42L9 22L7.5 20.5L6 22L4.5 20.5L3 22V2L4.5 3.5L6 2L7.5 3.5L9 2L10.5 3.5L12 2L13.5 3.5L15 2L16.5 3.5L18 2L19.5 3.5L21 2V11.1C22.28 12.41 23 14.17 23 16C23 19.87 19.86 23 16 23C14.14 23 12.36 22.26 11.05 20.95C10.88 20.78 10.72 20.61 10.58 20.42M9.72 19.09C9.4 18.43 9.18 17.73 9.07 17H6V15H9.07C9.17 14.29 9.38 13.62 9.68 13H6V11H11.1C12.37 9.76 14.1 9 16 9H6V7H18V9H16C17.05 9 18.07 9.24 19 9.68V4.91H5V19.09H9.72M20.85 16C20.85 13.32 18.67 11.15 16 11.15C14.71 11.15 13.5 11.66 12.57 12.57C11.66 13.5 11.15 14.71 11.15 16C11.15 18.68 13.32 20.85 16 20.85C16.64 20.85 17.27 20.73 17.86 20.5C18.44 20.24 19 19.88 19.43 19.43C19.88 19 20.24 18.44 20.5 17.86C20.73 17.27 20.85 16.64 20.85 16Z" /></svg>
                        </a>
                    @endif
                    @if($desk->code === null)
                        <a href="{{ route('desks.code.create', $desk) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    Generate code</title>
                                <path
                                    d="M5 5H7V7H5V5M1 1H11V11H1V1M3 3V9H9V3H3M5 17H7V19H5V17M1 13H11V23H1V13M3 15V21H9V15H3M13 13H17V15H19V13H23V15H19V17H23V23H19V21H15V23H13V21H15V19H13V13M21 21V19H19V21H21M19 17H17V15H15V19H19V17M17 2V5H14V7H17V10H19V7H22V5H19V2Z"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('desks.code.delete', $desk) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    Delete code</title>
                                <path
                                    d="M5 5H7V7H5V5M1 1H11V11H1V1M3 3V9H9V3H3M5 17H7V19H5V17M1 13H11V23H1V13M3 15V21H9V15H3M13 13H17V15H19V13H23V15H19V17H23V23H19V21H15V23H13V21H15V19H13V13M21 21V19H19V21H21M19 17H17V15H15V19H19V17M15.17 1.76L13.76 3.17L16.59 6L13.76 8.83L15.17 10.24L18 7.41L20.83 10.24L22.24 8.83L19.41 6L22.24 3.17L20.83 1.76L18 4.59L15.17 1.76Z"/>
                            </svg>
                        </a>
                    @endif
                </x-slot:actions>
                <div class="py-2">
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Description</dt>
                            <dd class="flex items-start gap-x-2">
                                <div
                                    class="font-medium text-gray-900 text-right dark:text-gray-100">{{ $desk->description }}</div>
                            </dd>
                        </div>
                    </dl>
                    @unless($desk->code === null)
                        <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                            <div class="flex justify-between gap-x-4 py-3">
                                <dt class="text-gray-500 dark:text-gray-300">Code</dt>
                                <dd class="flex items-start gap-x-2">
                                    <div class="font-medium text-gray-900 text-right dark:text-gray-100">
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            {{ $desk->code }}
                                        </span>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    @endunless
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Status</dt>
                            <dd class="flex items-start gap-x-2">
                                <div class="font-medium text-gray-900 text-right dark:text-gray-100">
                                    @if(!$desk->is_occupied)
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Free
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                            Occupied
                                        </span>
                                    @endif
                                </div>
                            </dd>
                        </div>
                    </dl>
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Seats</dt>
                            <dd class="flex items-start gap-x-2">
                                <div
                                    class="font-medium text-gray-900 text-right dark:text-gray-100">{{ $desk->number_of_seats }}</div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </x-item-card>
        @endforeach
        @foreach($groups as $group)
                <?php
                $seats = 0;
                $is_occupied = false;
                $grouped = [];
                foreach ($group->desks as $desk) {
                    $grouped[] = $desk->id;
                    $seats += $desk->number_of_seats;
                    if ($desk->is_occupied) $is_occupied = true;
                }
                ?>
            <x-item-card title="Group #{{ $group->id }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>group</title>
                        <path
                            d="M1,1V5H2V19H1V23H5V22H19V23H23V19H22V5H23V1H19V2H5V1M5,4H19V5H20V19H19V20H5V19H4V5H5M6,6V14H9V18H18V9H14V6M8,8H12V12H8M14,11H16V16H11V14H14"/>
                    </svg>
                </x-slot:icon>
                <x-slot:actions>
                    <a href="{{ route('groups.orders.create', $group) }}"
                       class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                Create order</title>
                            <path
                                d="M18 15V18H15V20H18V23H20V20H23V18H20V15H18M13.26 20.74L12 22L10.5 20.5L9 22L7.5 20.5L6 22L4.5 20.5L3 22V2L4.5 3.5L6 2L7.5 3.5L9 2L10.5 3.5L12 2L13.5 3.5L15 2L16.5 3.5L18 2L19.5 3.5L21 2V13.35C20.37 13.13 19.7 13 19 13V5H5V19H13C13 19.57 13.1 20.22 13.26 20.74M14.54 15C14 15.58 13.61 16.25 13.35 17H6V15H14.54M6 11H18V13H6V11M6 7H18V9H6V7Z"/>
                        </svg>
                    </a>
                    @if($is_occupied && $group->latestOrder)
                        <a href="{{ route('groups.orders.index', $group) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>View orders</title><path d="M15 16.69V13H16.5V15.82L18.94 17.23L18.19 18.53L15 16.69M10.58 20.42L9 22L7.5 20.5L6 22L4.5 20.5L3 22V2L4.5 3.5L6 2L7.5 3.5L9 2L10.5 3.5L12 2L13.5 3.5L15 2L16.5 3.5L18 2L19.5 3.5L21 2V11.1C22.28 12.41 23 14.17 23 16C23 19.87 19.86 23 16 23C14.14 23 12.36 22.26 11.05 20.95C10.88 20.78 10.72 20.61 10.58 20.42M9.72 19.09C9.4 18.43 9.18 17.73 9.07 17H6V15H9.07C9.17 14.29 9.38 13.62 9.68 13H6V11H11.1C12.37 9.76 14.1 9 16 9H6V7H18V9H16C17.05 9 18.07 9.24 19 9.68V4.91H5V19.09H9.72M20.85 16C20.85 13.32 18.67 11.15 16 11.15C14.71 11.15 13.5 11.66 12.57 12.57C11.66 13.5 11.15 14.71 11.15 16C11.15 18.68 13.32 20.85 16 20.85C16.64 20.85 17.27 20.73 17.86 20.5C18.44 20.24 19 19.88 19.43 19.43C19.88 19 20.24 18.44 20.5 17.86C20.73 17.27 20.85 16.64 20.85 16Z" /></svg>
                        </a>
                    @endif
                    @if($group->code === null)
                        <a href="{{ route('groups.code.create', $group) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    Generate code</title>
                                <path
                                    d="M5 5H7V7H5V5M1 1H11V11H1V1M3 3V9H9V3H3M5 17H7V19H5V17M1 13H11V23H1V13M3 15V21H9V15H3M13 13H17V15H19V13H23V15H19V17H23V23H19V21H15V23H13V21H15V19H13V13M21 21V19H19V21H21M19 17H17V15H15V19H19V17M17 2V5H14V7H17V10H19V7H22V5H19V2Z"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('groups.code.delete', $group) }}"
                           class="w-10 h-10 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    Delete code</title>
                                <path
                                    d="M5 5H7V7H5V5M1 1H11V11H1V1M3 3V9H9V3H3M5 17H7V19H5V17M1 13H11V23H1V13M3 15V21H9V15H3M13 13H17V15H19V13H23V15H19V17H23V23H19V21H15V23H13V21H15V19H13V13M21 21V19H19V21H21M19 17H17V15H15V19H19V17M15.17 1.76L13.76 3.17L16.59 6L13.76 8.83L15.17 10.24L18 7.41L20.83 10.24L22.24 8.83L19.41 6L22.24 3.17L20.83 1.76L18 4.59L15.17 1.76Z"/>
                            </svg>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('groups.destroy', $group) }}" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button
                            class="w-10 text-red-600 align-middle p-1 rounded border border-transparent hover:border-gray-300 active:bg-coffee-light-1 dark:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><title>
                                    Delete group</title>
                                <path
                                    d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"/>
                            </svg>
                        </button>
                    </form>
                </x-slot:actions>
                <div class="py-2">
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Description</dt>
                            <dd class="flex items-start gap-x-2">
                                <div
                                    class="font-medium text-gray-900 text-right dark:text-gray-100">{{ $group->description }}</div>
                            </dd>
                        </div>
                    </dl>
                    @unless($group->code === null)
                        <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                            <div class="flex justify-between gap-x-4 py-3">
                                <dt class="text-gray-500 dark:text-gray-300">Code</dt>
                                <dd class="flex items-start gap-x-2">
                                    <div class="font-medium text-gray-900 text-right dark:text-gray-100">
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            {{ $group->code }}
                                        </span>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    @endunless
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Status</dt>
                            <dd class="flex items-start gap-x-2">
                                <div class="font-medium text-gray-900 text-right dark:text-gray-100">
                                    @if(!$is_occupied)
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                          Free
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                            Occupied
                                        </span>
                                    @endif
                                </div>
                            </dd>
                        </div>
                    </dl>
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Grouped desks</dt>
                            <dd class="flex items-start gap-x-2">
                                <div
                                    class="font-medium text-gray-900 text-right dark:text-gray-100">{{ implode(', ', $grouped) }}</div>
                            </dd>
                        </div>
                    </dl>
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Seats</dt>
                            <dd class="flex items-start gap-x-2">
                                <div class="font-medium text-gray-900 text-right dark:text-gray-100">{{ $seats }}</div>
                            </dd>
                        </div>
                    </dl>
                    <dl class="-my-3 divide-y divide-gray-100 px-4 py-1 text-sm leading-6">
                        <div class="flex justify-between gap-x-4 py-3">
                            <dt class="text-gray-500 dark:text-gray-300">Group created</dt>
                            <dd class="flex items-start gap-x-2">
                                <div
                                    class="font-medium text-gray-900 text-right dark:text-gray-100">{{ $group->created_at }}</div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </x-item-card>
        @endforeach
    </ul>
    <div
        class="mt-4 p-1 font-semibold text-gray-500 uppercase sm:grid-cols-9 dark:text-gray-400"
    >
        {{ $desks->links() }}
    </div>
</div>
