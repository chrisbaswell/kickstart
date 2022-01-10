<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <textarea
        {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
        {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
        {!! $isAutofocused() ? 'autofocus' : null !!}
        {!! ($cols = $getCols()) ? "cols=\"{$cols}\"" : null !!}
        {!! $isDisabled() ? 'disabled' : null !!}
        id="{{ $getId() }}"
        {!! ($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
        {!! ($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
        {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
        {!! $isRequired() ? 'required' : null !!}
        {!! ($rows = $getRows()) ? "rows=\"{$rows}\"" : null !!}
        {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->merge($getExtraInputAttributeBag()->getAttributes())
                ->class([
                    'block border w-full p-3 transition duration-75 rounded-lg outline-none appearance-none focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500',
                    'border-base-300' => ! $errors->has($getStatePath()),
                    'border-danger-500 ring-danger-500' => $errors->has($getStatePath()),
                    'bg-white' => ! $isDisabled(),
                    'bg-base-100' => $isDisabled()
                ])
        }}
        @if ($shouldAutosize())
            x-data="textareaFormComponent()"
            x-on:input="render()"
            {{ $getExtraAlpineAttributeBag() }}
        @endif
    ></textarea>
</x-forms::field-wrapper>
