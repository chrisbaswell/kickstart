@php
    $stateColor = match ($getStateColor()) {
        'danger' => 'text-danger-600',
        'primary' => 'text-primary-600',
        'success' => 'text-success-600',
        'warning' => 'text-warning-600',
        default => 'text-base-700',
    };
@endphp

<div {{ $attributes->merge($getExtraAttributes())->class(['px-4 py-3']) }}>
    @if ($getStateIcon())
        <x-dynamic-component
            :component="$getStateIcon()"
            :class="'w-6 h-6' . ($stateColor ? ' ' . $stateColor : '')"
        />
    @endif
</div>
