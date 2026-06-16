<x-app-layout>
    <x-slot:title>Manage Group {{ $group->id }}</x-slot:title>
    <x-slot:actions>
        <x-admin-button form="delete-form">
            Delete Group
        </x-admin-button>
    </x-slot:actions>

    <div class="mb-2 mt-4 text-x">
        Description: {{ $group->description }}
        <br>
        Desks:
        <ul>
            @foreach($group->desks as $desk)
                <li>{{ $desk->id }} - {{ $desk->description }}</li>
            @endforeach
        </ul>
    </div>
    <p>This section should contain information about the group, maybe the orders </p>

    <form method="POST" action="/groups/{{$group->id}}" id="delete-form" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
