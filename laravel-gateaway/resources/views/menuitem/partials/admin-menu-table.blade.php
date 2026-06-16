<div class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-coffee-dark-3">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
            <tr
                class="h-12 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
            >
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">ETA</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Quantity</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2">
            @foreach($items as $item)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                            <div
                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                            >
                                <img
                                    class="object-cover w-full h-full rounded-full"
                                    src="{{ $item['image_path'] ?? 'https://placehold.co/100x100?text=' . $item->name[0] }}"
                                    alt=""
                                    loading="lazy"
                                />
                                <div
                                    class="absolute inset-0 rounded-full shadow-inner"
                                    aria-hidden="true"
                                ></div>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $item->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $item->category }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $item->ETAinMinutes }} min
                    </td>
                    <td class="px-4 h-full my-3 text-sm line-clamp-2 overflow-clip align-middle">
                        {{ $item->description }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        <span
                            class="{{ $item->quantity < 20 ? ($item->quantity < 10 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') : 'bg-green-100 text-green-700' }} px-2 py-1 font-semibold leading-tight rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                          {{ $item->quantity }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $item->price }} lei
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-4 text-sm">
                            <a href="{{ route('menu.edit', $item) }}"
                               class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-coffee rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                               aria-label="Edit"
                            >
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                    ></path>
                                </svg>
                            </a>
                            <form action="{{ route('menu.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-coffee rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                    aria-label="Delete"
                                    type="submit"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        aria-hidden="true"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
        <div
            class="min-h-12 py-1.5 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3">
            {{ $items->links() }}
        </div>
    @endif
</div>
