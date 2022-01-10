<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div
        x-data="tagsInputFormComponent({
            state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
        })"
        id="{{ $getId() }}"
        {{ $attributes->merge($getExtraAttributes()) }}
        {{ $getExtraAlpineAttributeBag() }}
    >
        <div
            x-show="state.length || {{ $isDisabled() ? 'false' : 'true' }}"
            @class([
                'block border w-full h-10 px-3 transition duration-75 rounded-lg divide-y focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500',
                'border-base-300' => ! $errors->has($getStatePath()),
                'border-danger-500 ring-danger-500' => $errors->has($getStatePath()),
            ])
        >
            @unless ($isDisabled())
                <div>
                    <input
                        autocomplete="off"
                        {!! $isAutofocused() ? 'autofocus' : null !!}
                        id="{{ $getId() }}"
                        list="{{ $getId() }}-suggestions"
                        {!! $getPlaceholder() ? 'placeholder="' . $getPlaceholder() . '"' : null !!}
                        type="text"
                        x-on:keydown.enter.stop.prevent="createTag()"
                        x-on:keydown.,.stop.prevent="createTag()"
                        x-on:blur="createTag()"
                        x-model="newTag"
                        {{ $getExtraInputAttributeBag()->class(['block w-full border-0 h-10 px-3 outline-none appearance-none']) }}
                    />

                    <datalist id="{{ $getId() }}-suggestions">
                        @foreach ($getSuggestions() as $suggestion)
                            <template x-if="! state.includes('{{ $suggestion }}')" x-bind:key="'{{ $suggestion }}'">
                                <option value="{{ $suggestion }}" />
                            </template>
                        @endforeach
                    </datalist>
                </div>
            @endunless

            <div
                x-show="state.length"
                class="overflow-hidden rtl:space-x-reverse relative w-full px-1 py-1 bg-base-50 border-t border-base-300"
            >
                <div class="flex flex-wrap gap-1">
                    <template class="inline" x-for="tag in state" x-bind:key="tag">
                        <button
                            @unless ($isDisabled())
                                x-on:click="deleteTag(tag)"
                            @endunless
                            type="button"
                            @class([
                                'inline-flex items-center justify-center h-6 px-2 my-1 text-sm font-medium tracking-tight text-primary-700 rounded-full bg-primary-500/10 space-x-1',
                                'cursor-default' => $isDisabled(),
                            ])
                        >
                            <span x-text="tag"></span>

                            @unless ($isDisabled())
                                <x-heroicon-s-x class="w-3 h-3" />
                            @endunless
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-forms::field-wrapper>
