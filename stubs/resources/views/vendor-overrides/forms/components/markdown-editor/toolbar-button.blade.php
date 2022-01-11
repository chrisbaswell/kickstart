<button
    x-bind:disabled="tab === 'preview'"
    type="button"
    {{ $attributes->class(['border-base-300 bg-white text-base text-base-800 text-xs py-1 px-3 cursor-pointer font-medium border rounded transition duration-200 hover:bg-base-100 focus:ring-primary-200 focus:ring focus:ring-opacity-50']) }}
>
    {{ $slot }}
</button>
