@php
    $action = $getAction();
    $record = $getRecord();

    if (! $action) {
        $clickAction = null;
    } elseif ($record) {
        $clickAction = "mountTableAction('{$getName()}', '{$record->getKey()}')";
    } else {
        $clickAction = "mountTableAction('{$getName()}')";
    }
@endphp

<x-kickstart::button
    :tag="((! $action) && $url) ? 'a' : 'button'"
    :wire:click="$clickAction"
    :href="$getUrl()"
    :target="$shouldOpenUrlInNewTab() ? '_blank' : null"
    :color="$getColor()"
    :icon="$getIcon()"
    :icon-position="$getIconPosition()"
    size="sm"
>
    {{ $getLabel() }}
</x-kickstart::button>
