<x-app-layout :showBackButton="false">
    <x-slot:title>Staff</x-slot:title>
    <x-slot:actions>
        <x-admin-button href="{{ route('profile.create') }}">
            Add staff member
        </x-admin-button>
    </x-slot:actions>

    <div class="w-full overflow-hidden rounded-lg shadow-xs border dark:border-coffee-dark-3">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                <tr
                    class="h-11 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-coffee-dark-3 bg-coffee-light-3 dark:text-gray-400 dark:bg-coffee-dark-3"
                >
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Created at</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
                </thead>
                <tbody
                    class="bg-white divide-y dark:divide-coffee-dark-3 dark:bg-coffee-dark-2"
                >
                @foreach($users as $user)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 my-3 text-sm overflow-clip">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $user->phone }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $user->role ?? '(admin)' }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="{{ $user['is_active'] ? 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100' : 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100' }} px-2 py-1 font-semibold leading-tight rounded-full">
                              {{ $user['is_active'] ? 'active' : 'inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $user->created_at->format('Y/m/d') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <x-admin-button href="{{ route('profile.edit-admin', $user) }}">
                                    Edit
                                </x-admin-button>
                                <x-admin-button
                                    class="{{ !$user->role ? 'opacity-50 cursor-not-allowed' : 'flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-coffee rounded-lg focus:outline-none focus:shadow-outline-gray' }} "
                                    aria-label="Delete"
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')"
                                    :disabled="!$user->role"
                                >
                                    Delete
                                </x-admin-button>
                                <x-modal name="confirm-user-deletion-{{ $user->id }}" focusable>
                                    <form method="post" action="{{ route('profile.destroy', $user) }}" class="p-6">
                                        @csrf
                                        @method('delete')

                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this account?') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Once this account is deleted, all of its resources and data will be permanently deleted.') }}
                                        </p>

                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                {{ __('Cancel') }}
                                            </x-secondary-button>

                                            <x-danger-button class="ms-3">
                                                {{ __('Delete Account') }}
                                            </x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="min-h-12 py-1.5 px-2 font-semibold text-gray-500 uppercase border-t dark:border-coffee-dark-3 bg-coffee-light-3 sm:grid-cols-9 dark:text-gray-400 dark:bg-coffee-dark-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
