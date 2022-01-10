<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class(['space-y-2']) }}>
        @if (count($containers = $getChildComponentContainers()))
            <ul class="space-y-2">
                @foreach ($containers as $uuid => $item)
                    <li
                        x-data="{ isCreateButtonDropdownOpen: false, isCreateButtonVisible: false }"
                        x-on:click="isCreateButtonVisible = true"
                        x-on:click.away="isCreateButtonVisible = false"
                        wire:key="{{ $item->getStatePath() }}"
                        class="relative p-6 bg-white shadow-sm rounded-lg border border-base-300"
                    >
                        {{ $item }}

                        @unless ($isItemDeletionDisabled() && ($isItemMovementDisabled() && ($loop->count <= 1)))
                            <div class="absolute top-0 right-0 h-6 flex divide-x rounded-bl-lg rounded-tr-lg border-base-300 border-b border-l overflow-hidden">
                                @unless ($loop->first || $isItemMovementDisabled())
                                    <button
                                        wire:click="dispatchFormEvent('builder::moveItemUp', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        type="button"
                                        class="flex items-center justify-center w-6 text-base-800 hover:bg-base-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-white focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600"
                                    >
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.move_item_up.label') }}
                                        </span>

                                        <x-heroicon-s-chevron-up class="w-4 h-4" />
                                    </button>
                                @endunless

                                @unless ($loop->last || $isItemMovementDisabled())
                                    <button
                                        wire:click="dispatchFormEvent('builder::moveItemDown', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        type="button"
                                        class="flex items-center justify-center w-6 text-base-800 hover:bg-base-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-white focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600"
                                    >
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.move_item_down.label') }}
                                        </span>

                                        <x-heroicon-s-chevron-down class="w-4 h-4" />
                                    </button>
                                @endunless

                                @unless ($isItemDeletionDisabled())
                                    <button
                                        wire:click="dispatchFormEvent('builder::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                        type="button"
                                        class="flex items-center justify-center w-6 text-danger-600 hover:bg-base-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-white focus:ring-primary-600 focus:text-danger-600 focus:bg-primary-50 focus:border-primary-600"
                                    >
                                        <span class="sr-only">
                                            {{ __('forms::components.repeater.buttons.delete_item.label') }}
                                        </span>

                                        <x-heroicon-s-trash class="w-4 h-4" />
                                    </button>
                                @endunless
                            </div>
                        @endunless

                        @if ((! $loop->last) && (! $isItemCreationDisabled()) && (blank($getMaxItems()) || ($getMaxItems() > $getItemsCount())))
                            <div
                                x-show="isCreateButtonVisible || isCreateButtonDropdownOpen"
                                x-transition
                                class="absolute bottom-0 inset-x-0 -mb-7 z-10 h-12 flex items-center justify-center"
                            >
                                <div class="relative flex justify-center">
                                    <button
                                        x-on:click="isCreateButtonDropdownOpen = true"
                                        type="button"
                                        class="flex items-center justify-center h-8 w-8 rounded-full border border-base-300 text-base-800 bg-white hover:bg-base-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-white focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50"
                                        x-bind:class="{
                                            'bg-base-50': isCreateButtonDropdownOpen,
                                        }"
                                    >
                                        <span class="sr-only">
                                            {{ $getCreateItemBetweenButtonLabel() }}
                                        </span>

                                        <x-heroicon-o-plus class="w-5 h-5" />
                                    </button>

                                    <x-forms::builder.block-picker
                                        :blocks="$getBlocks()"
                                        :create-after-item="$uuid"
                                        :state-path="$getStatePath()"
                                    />
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if ((! $isItemCreationDisabled()) && (blank($getMaxItems()) || ($getMaxItems() > $getItemsCount())))
            <div x-data="{ isCreateButtonDropdownOpen: false }" class="relative flex justify-center">
                <button
                    x-on:click="isCreateButtonDropdownOpen = true"
                    type="button"
                    class="w-full h-9 px-4 inline-flex space-x-1 items-center justify-center font-medium tracking-tight rounded-lg text-base-800 bg-white border border-base-300 hover:bg-base-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600"
                >
                    <x-heroicon-s-plus class="w-5 h-5" />

                    {{ $getCreateItemButtonLabel() }}
                </button>

                <x-forms::builder.block-picker
                    :blocks="$getBlocks()"
                    :state-path="$getStatePath()"
                />
            </div>
        @endif
    </div>
</x-forms::field-wrapper>
