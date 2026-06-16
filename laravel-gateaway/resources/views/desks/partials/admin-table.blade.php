@props(['desks'])

<div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-coffee-dark-3">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
            <tr
                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
            >
                <th class="px-4 py-3">Desk</th>
                <th class="px-4 py-3">Group Status</th>
                <th class="px-4 py-3">Occupation</th>
                <th class="px-4 py-4">Number of Seats</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
            </thead>
            <tbody
                class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2"
            >
            @foreach($desks as $desk)
                <tr class="text-gray-700 dark:text-gray-300">
                    <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                            <div>
                                <p class="font-semibold">{{ $desk->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($desk->joined_at)
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                            >
                                    Joined
                                </span>
                        @else
                            <span
                                class="px-2 py-1 font-semibold text-nowrap leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100"
                            >
                                    Not Joined
                                </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$desk->is_occupied)
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                            >
                                  Unoccupied
                                </span>
                        @else
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100"
                            >
                                    Occupied
                                </span>
                        @endif
                    </td>
                    <td class="px4 py-3 text-sm text-center">
                        <p class="font-semibold">
                            {{ $desk->number_of_seats }}
                        </p>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <p class="font-semibold">{{ $desk->description }}</p>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-4 text-sm">
                            <x-admin-button href="{{ route('desks.orders.index', $desk) }}">
                                See Orders
                            </x-admin-button>
                            @if($desk->joined_at)
                                @foreach($groups as $group)
                                    @if($group->desks->contains($desk->id))
                                        <x-admin-button href="{{ route('groups.edit', $group) }}">
                                            Check Group
                                        </x-admin-button>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="min-h-12 py-1.5 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3">
        {{ $desks->links() }}
    </div>

</div>
