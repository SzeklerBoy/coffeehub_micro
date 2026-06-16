<x-app-layout>
    <x-slot:title>Create new profile</x-slot:title>

    <form method="POST" action="{{ route('profile.store') }}" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-x-3 xl:gap-x-5 px-5 py-3 bg-white shadow rounded-lg dark:bg-coffee-dark-3">
        @csrf
        <x-input name="name" placeholder="John Doe" required>Name</x-input>
        <x-input
            name="email"
            type="email"
            placeholder="john@example.com"
        >
            Email
        </x-input>
        <x-input
            name="phone"
            type="tel"
        >
            Phone number
        </x-input>
        <x-input
            name="role"
            placeholder="waitress"
            required
        >
            Role
        </x-input>
        <x-input
            name="password"
            type="password"
            required
        >
            Password
        </x-input>
        <x-input
            name="password_confirmation"
            type="password"
            required
        >
            Password confirmation
        </x-input>
        <x-checkbox name="status" value="1" label="active"> Status </x-checkbox>
        <div class="xl:col-start-3 flex justify-end mt-3">
            <x-secondary-button href="{{ route('profile.index') }}" class="my-3 h-full">
                Cancel
            </x-secondary-button>
            <x-admin-button class="my-3 ml-3">
                Save
            </x-admin-button>
        </div>
    </form>
</x-app-layout>
