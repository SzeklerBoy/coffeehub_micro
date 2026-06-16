<x-app-layout>
    <x-slot:title>{{ __('Create New Group') }}</x-slot:title>

    <form method="POST" action="/groups">
        @csrf
        <div
            class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-coffee-dark-0"
        >
            <x-input
                type="text"
                name="description"
                placeholder=""
            >Group description</x-input>

            <label class="block text-sm mt-5">
                <span class="text-gray-700 dark:text-gray-300">Desks:</span>
            </label>
            @foreach($desks as $desk)
                <div>
                    <x-checkbox
                        name="desks[]"
                        value="{{ $desk->id }}"
                        id="desk-{{ $desk->id }}"
                        class="form-checkbox"
                        label="{{ $desk->id }} - {{ $desk->description }}"
                    ></x-checkbox>
                </div>
            @endforeach
            @error('desks')
            <span class="text-red-500 text-sm">{{ $message }}</span><br>
            @enderror


            <x-admin-button class="mt-4">
                Create Group
            </x-admin-button>
        </div>

    </form>

</x-app-layout>
