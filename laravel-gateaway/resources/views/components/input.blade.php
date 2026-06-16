@props(['name', 'options' => []])

<div>
    <label class="block text-sm">
        <span class="text-gray-700 dark:text-gray-300">{{ $slot }}</span>
        @if($attributes->get('type') === 'textarea')
            <textarea
                name="{{ $name }}"
            {{ $attributes->merge([
                'type' => 'text',
                'class' => 'block w-full mt-1 text-sm rounded dark:border-gray-600 dark:bg-coffee-dark-2 focus:border-coffee focus:outline-none focus:ring-coffee dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
            ]) }}
        >{{ $attributes->get('value') }}</textarea>
        @else
            <input
                name="{{ $name }}"
                {{ $attributes->merge([
                    'type' => 'text',
                    'class' => 'block w-full mt-1 text-sm rounded dark:border-gray-600 dark:bg-coffee-dark-2 focus:border-coffee focus:outline-none focus:ring-coffee dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]) }}
            />
        @endif
    </label>
    @error($name)
    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
