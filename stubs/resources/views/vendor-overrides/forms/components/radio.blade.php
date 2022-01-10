<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    @if ($isInline())
        <x-slot name="labelSuffix">
    @endif
            <div {{ $attributes->merge($getExtraAttributes())->class([
                'gap-2',
                'flex flex-wrap gap-3' => $isInline(),
            ]) }}>
                @foreach ($getOptions() as $value => $label)
                    <div @class([
                        'flex items-start',
                        'gap-3' => ! $isInline(),
                        'gap-2' => $isInline(),
                    ])>
                        <div class="flex items-center h-5">
                            <input
                                name="{{ $getId() }}"
                                id="{{ $getId() }}-{{ $value }}"
                                type="radio"
                                value="{{ $value }}"
                                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                                {{ $getExtraInputAttributeBag()->class([
                                    'focus:ring-primary-600 h-4 w-4 text-primary-600',
                                    'border-base-300' => ! $errors->has($getStatePath()),
                                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                                ]) }}
                                {!! $isOptionDisabled($value, $label) ? 'disabled' : null !!}
                            />
                        </div>

                        <div class="text-sm">
                            <label for="{{ $getId() }}-{{ $value }}" @class([
                                'font-medium',
                                'text-base-700' => ! $errors->has($getStatePath()),
                                'text-danger-600' => $errors->has($getStatePath()),
                            ])>
                                {{ $label }}
                            </label>

                            @if ($hasDescription($value))
                                <p class="text-base-500">
                                    {{ $getDescription($value) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
    @if ($isInline())
        </x-slot>
    @endif
</x-forms::field-wrapper>
