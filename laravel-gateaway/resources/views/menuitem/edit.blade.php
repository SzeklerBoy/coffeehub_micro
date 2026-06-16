<x-app-layout>
    <x-slot:title>Edit menu item</x-slot:title>
    <form method="POST" action="{{ route('menu.update', $item) }}" enctype="multipart/form-data" class="px-5 py-3 bg-white border rounded-lg dark:bg-coffee-dark-3 dark:border-none">
        @csrf
        @method('PATCH')
        <div class="flex flex-col sm:flex-row gap-5 md:gap-10">
            <div class="flex-none sm:w-60">
                <x-input name="image" type="file" accept="image/*">Image</x-input>
                <label class="block text-sm mb-1 mt-5 text-gray-600 dark:text-gray-300">Old image</label>
                <img src="{{ $item['image_path'] ?? 'https://placehold.co/250x250?text=' . $item->name[0] }}" class="border rounded-xl aspect-square object-cover" alt="Menu item image"/>
            </div>
            <div class="flex flex-col flex-grow gap-5">
                <x-input name="name" placeholder="Espresso" :value="$item->name" required>Name</x-input>
                <x-input
                    name="description"
                    type="textarea"
                    :value="$item->description"
                    placeholder="A concentrated form of coffee produced by forcing hot water under high pressure through finely-ground coffee beans."
                >
                    Description
                </x-input>
                <x-input
                    name="quantity"
                    type="number"
                    min="0"
                    step="0.01"
                    :value="$item->quantity"
                    placeholder="25"
                    required
                >
                    Quantity
                </x-input>
                <x-input
                    name="price"
                    type="number"
                    min="0"
                    step="0.01"
                    :value="$item->price"
                    placeholder="5"
                    required
                >
                    Price
                </x-input>
                <x-input
                    name="ETAinMinutes"
                    type="number"
                    min="0"
                    step="1"
                    :value="$item->ETAinMinutes"
                    placeholder="5"
                    required>
                    Time to prepare (minutes)
                </x-input>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            <x-secondary-button href="{{ route('menu.index') }}" class="my-3 h-full">
                Cancel
            </x-secondary-button>
            <x-admin-button class="my-3 ml-3">
                Update
            </x-admin-button>
        </div>
    </form>
</x-app-layout>
