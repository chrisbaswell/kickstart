<fieldset
    {!! $getId() ? "id=\"{$getId()}\"" : null !!}
    {{ $attributes->merge($getExtraAttributes())->class(['rounded-xl border border-base-300 p-6']) }}
>
    <legend class="text-sm leading-tight font-medium px-2 -ml-2">
        {{ $getLabel() }}
    </legend>

    {{ $getChildComponentContainer() }}
</fieldset>
