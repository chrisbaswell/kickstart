@props([
    'color' => 'primary',
    'icon' => null,
    'label' => null,
    'tag' => 'button',
    'type' => 'button',
])

@php
    $buttonClasses = [
        'flex items-center justify-center w-10 h-10 rounded-full hover:bg-base-500/5 focus:outline-none',
        'text-primary-600 focus:bg-primary-500/10' => $color === 'primary',
        'text-danger-600 focus:bg-danger-500/10' => $color === 'danger',
        'text-base-600 focus:bg-base-500/10' => $color === 'secondary',
        'text-success-600 focus:bg-success-500/10' => $color === 'success',
        'text-warning-600 focus:bg-warning-500/10' => $color === 'warning',
    ];

    $iconClasses = 'w-5 h-5';
@endphp

@if ($tag === 'button')
    <button
        type="{{ $type }}"
        {{ $attributes->class($buttonClasses) }}
    >
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        <x-dynamic-component :component="$icon" :class="$iconClasses" />
    </button>
@elseif ($tag === 'a')
    <a {{ $attributes->class($buttonClasses) }}>
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        <x-dynamic-component :component="$icon" :class="$iconClasses" />
    </a>
@endif
