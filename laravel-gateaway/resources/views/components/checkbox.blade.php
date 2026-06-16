@props(['name', 'label'])

<label class="block text-sm mb-1">
    @if($slot->isNotEmpty())
        <span class="align-middle text-gray-700 dark:text-gray-400">{{ $slot }}</span>
        <br>
    @endif
    <input
        name="{{ $name }}"
        type="checkbox"
        {{ $attributes->merge([
            'class' => 'aspect-square text-sm rounded checked:bg-coffee checked:focus:bg-coffee checked:hover:bg-coffee-lighter checked:hover:focus:bg-coffee-lighter focus:border-coffee focus:outline-none focus:ring-coffee'
        ]) }}
    />
    @isset($label)
        <span class="ml-1 dark:text-white"> {{ $label }} </span>
    @endisset
</label>
@foreach($errors->get($name) as $error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
@endforeach
