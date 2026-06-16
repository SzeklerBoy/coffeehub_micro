<x-app-layout>
    <x-slot:title>Create new desk</x-slot:title>

    <form method="POST" action="/desks">
        @csrf
        <div
            class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-coffee-dark-3"
        >
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Number of seats</span>
                <x-input
                    type="number"
                    name="number_of_seats"
                    placeholder="5"
                />
            </label>
            @error('number_of_seats')
            <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <label class="block text-sm mt-4">
                <span class="text-gray-700 dark:text-gray-400">Description</span>
                <x-input
                    name="description"
                    placeholder="Next to the window"
                />
            </label>
            @error('description')
            <span class="text-red-500 text-xs">{{ $message }}</span><br>
            @enderror
            <div class="flex justify-end">
                <x-admin-button class="mt-4">
                    Save
                </x-admin-button>
            </div>
        </div>

    </form>

</x-app-layout>
