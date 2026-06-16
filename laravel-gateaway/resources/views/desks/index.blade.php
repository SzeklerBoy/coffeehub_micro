<x-app-layout :showBackButton="false">
    <x-slot:title>
        {{ __('Desks') }}
    </x-slot>
    <x-slot:actions>
        @if(Auth::user()->getAttribute('role') === null)
            <x-admin-button href="/desks/create">
                Create New Desk
            </x-admin-button>
        @endif
        <x-admin-button href="/groups/create">
            Merge Desks
        </x-admin-button>
    </x-slot>

    @if(Auth::user()->getAttribute('role') === null)
        @include('desks.partials.admin-table')
    @else
        @include('desks.partials.staff-table')
    @endif

</x-app-layout>
