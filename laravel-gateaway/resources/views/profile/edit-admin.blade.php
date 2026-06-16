<x-app-layout>
    <x-slot:title>Staff profile of {{$user->name}}</x-slot:title>

    <div class="flex flex-col mt-8">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-gray-800 mt-6 p-4">
            <form action="{{route('profile.update-admin', $user->id)}}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <x-input-label for="name" :value="__('Name')" class="mt-4"/>
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-1/2"
                              :value="old('name', $user->name)"
                              required autofocus autocomplete="name"/>
                <x-input-error class="mt-2" :messages="$errors->get('name')"/>

                <x-input-label for="email" :value="__('Email')" class="mt-4"/>
                <x-text-input id="email" name="email" type="email" class="mt-1 block
            w-1/2" :value="old('email', $user->email)" required autocomplete="username"/>
                <x-input-error class="mt-2" :messages="$errors->get('email')"/>

                <x-input-label for="phone" :value="__('Phone') " class="mt-4"/>
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block
            w-1/2" :value="old('phone',$user->phone)" required autocomplete="phone"/>
                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>

                <x-input-label for="role" :value="__('Role')" class="mt-4"/>
                <x-text-input id="role" name="role" type="text" class="mt-1 block
            w-1/2" :value="old('role', $user->role)" required autocomplete="role"/>
                <x-input-error class="mt-2" :messages="$errors->get('role')"/>

                <x-input-label for="is_active" :value="__('Is active?')" class="mt-4"/>
                <x-text-input id="is_active" name="is_active" type="checkbox"/>
                <x-input-error class="mt-2" :messages="$errors->get('is_active')"/>

                <div class="flex items-center gap-4 mt-4">
                    <x-admin-button
                    >{{ __('Save') }}</x-admin-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
