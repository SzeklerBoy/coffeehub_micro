<x-app-layout>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <div x-data="{openModal: false }">
        <x-slot:title>Create menu item</x-slot:title>
        <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data"
              class="flex flex-col gap-3 px-5 py-3 bg-white shadow rounded-lg dark:bg-coffee-dark-3">
            @csrf
            <x-input name="image" type="file" accept="image/*">Image</x-input>
            <x-input name="category" type="text" id="category-autocomplete" required>
                Category
            </x-input>
            <script>
                $(function () {
                    const categories = @json($categories);

                    $("#category-autocomplete").autocomplete({
                        source: categories
                    });
                });
            </script>
            <x-input name="name" placeholder="Espresso" required>Name</x-input>
            <x-input
                name="description"
                type="textarea"
                placeholder="A concentrated form of coffee produced by forcing hot water under high pressure through finely-ground coffee beans."
            >
                Description
            </x-input>
            <x-input name="ETA" placeholder="5" type="number" min="0" required>
                Time to prepare (minutes)
            </x-input>
            <x-input
                name="quantity"
                type="number"
                min="0"
                step="0.01"
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
                placeholder="5"
                required
            >
                Price
            </x-input>
            <div class="flex justify-end mt-3">
                <x-secondary-button href="{{ route('menu.index') }}" class="my-3 h-full">
                    Cancel
                </x-secondary-button>
                <x-admin-button class="my-3 ml-3">
                    Save
                </x-admin-button>
            </div>
        </form>
    </div>
</x-app-layout>
