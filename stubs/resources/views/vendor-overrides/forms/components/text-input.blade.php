@php
    $datalistOptions = $getDatalistOptions();

    $sideLabelClasses = [
        'whitespace-nowrap group-focus-within:text-primary-500',
        'text-base-400' => ! $errors->has($getStatePath()),
        'text-danger-400' => $errors->has($getStatePath()),
    ];
@endphp

<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class(['flex items-center space-x-1 group']) }}>
        @if ($label = $getPrefixLabel())
            <span @class($sideLabelClasses)>
                {{ $label }}
            </span>
        @endif

        <div class="flex-1">
            <input
                @unless ($hasMask())
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                    type="{{ $getType() }}"
                @else
                    x-data="textInputFormComponent({
                        {{ $hasMask() ? "getMaskOptionsUsing: (IMask) => ({$getJsonMaskConfiguration()})," : null }}
                        state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
                    })"
                    type="text"
                    wire:ignore
                    {{ $getExtraAlpineAttributeBag() }}
                @endunless
                {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isAutofocused() ? 'autofocus' : null !!}
                {!! $isDisabled() ? 'disabled' : null !!}
                id="{{ $getId() }}"
                {!! ($inputMode = $getInputMode()) ? "inputmode=\"{$inputMode}\"" : null !!}
                {!! $datalistOptions ? "list=\"{$getId()}-list\"" : null !!}
                {!! ($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                {!! ($value = $getMaxValue()) ? "max=\"{$value}\"" : null !!}
                {!! ($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                {!! ($value = $getMinValue()) ? "min=\"{$value}\"" : null !!}
                {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                {!! ($interval = $getStep()) ? "step=\"{$interval}\"" : null !!}
                {!! $isRequired() ? 'required' : null !!}
                {{ $getExtraInputAttributeBag()->class([
                    'block w-full h-10 px-4 transition duration-75 rounded-lg appearance-none focus:border-base-600 focus:ring-1 focus:ring-inset focus:ring-base-600',
                    'border-base-300' => ! $errors->has($getStatePath()),
                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                    'bg-white' => ! $isDisabled(),
                    'bg-base-100' => $isDisabled()
                ]) }}
            />
        </div>

        @if ($label = $getPostfixLabel())
            <span @class($sideLabelClasses)>
                {{ $label }}
            </span>
        @endif
    </div>

    @if ($datalistOptions)
        <datalist id="{{ $getId() }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}" />
            @endforeach
        </datalist>
    @endif
</x-forms::field-wrapper>
