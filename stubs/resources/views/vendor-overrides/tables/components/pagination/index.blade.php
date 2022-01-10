@props([
    'paginator',
    'recordsPerPageSelectOptions',
])

@php
    $isSimple = ! $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator;
@endphp

<nav
    role="navigation"
    aria-label="{{ __('tables::table.pagination.label') }}"
    class="flex items-center justify-between"
>
    <div class="flex justify-between items-center flex-1 lg:hidden">
        <div class="w-10">
            @if ($paginator->hasPages() && (! $paginator->onFirstPage()))
                <x-tables::icon-button
                    :wire:click="'previousPage(\'' . $paginator->getPageName() . '\')'"
                    rel="prev"
                    icon="heroicon-o-chevron-left"
                    :label="__('tables::table.pagination.buttons.previous.label')"
                />
            @endif
        </div>

        <x-tables::pagination.records-per-page-selector :options="$recordsPerPageSelectOptions" />

        <div class="w-10">
            @if ($paginator->hasPages() && $paginator->hasMorePages())
                <x-tables::icon-button
                    :wire:click="'nextPage(\'' . $paginator->getPageName() . '\')'"
                    rel="next"
                    icon="heroicon-o-chevron-right"
                    :label="__('tables::table.pagination.buttons.next.label')"
                />
            @endif
        </div>
    </div>

    <div class="hidden flex-1 items-center lg:grid grid-cols-3">
        <div class="flex items-center">
            @if ($isSimple)
                <x-kickstart::button
                    :wire:click="'previousPage(\'' . $paginator->getPageName() . '\')'"
                    icon="heroicon-s-chevron-left"
                    :disabled="$paginator->onFirstPage()"
                    rel="prev"
                    size="sm"
                    color="secondary"
                >
                    {{ __('tables::table.pagination.buttons.previous.label') }}
                </x-kickstart::button>
            @else
                <div class="pl-2 text-sm font-medium">
                    @if ($paginator->total() > 1)
                        {{ __('tables::table.pagination.overview', [
                            'first' => $paginator->firstItem(),
                            'last' => $paginator->lastItem(),
                            'total' => $paginator->total(),
                        ]) }}
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-center">
            <x-tables::pagination.records-per-page-selector :options="$recordsPerPageSelectOptions" />
        </div>

        <div class="flex items-center justify-end">
            @if ($isSimple)
                <x-kickstart::button
                    :wire:click="'nextPage(\'' . $paginator->getPageName() . '\')'"
                    icon="heroicon-s-chevron-right"
                    icon-position="after"
                    :disabled="! $paginator->hasMorePages()"
                    rel="next"
                    size="sm"
                    color="secondary"
                >
                    {{ __('tables::table.pagination.buttons.next.label') }}
                </x-kickstart::button>
            @else
                @if ($paginator->hasPages())
                    <div class="py-3 border rounded-lg">
                        <ol class="flex items-center text-sm text-base-500 divide-x divide-base-300">
                            <x-tables::pagination.item
                                :wire:click="'previousPage(\'' . $paginator->getPageName() . '\')'"
                                icon="heroicon-s-chevron-left"
                                aria-label="{{ __('tables::table.pagination.buttons.previous.label') }}"
                                :disabled="$paginator->onFirstPage()"
                                rel="prev"
                            />

                            @foreach ($paginator->render()->offsetGet('elements') as $element)
                                @if (is_string($element))
                                    <x-tables::pagination.item :label="$element" disabled />
                                @endif

                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        <x-tables::pagination.item
                                            :wire:click="'gotoPage(' . $page . ', \'' . $paginator->getPageName() . '\')'"
                                            :label="$page"
                                            :aria-label="__('tables::table.pagination.buttons.go_to_page.label', ['page' => $page])"
                                            :active="$page === $paginator->currentPage()"
                                            :wire:key="'pagination-' . $paginator->getPageName() . '-page' . $page"
                                        />
                                    @endforeach
                                @endif
                            @endforeach

                            <x-tables::pagination.item
                                :wire:click="'nextPage(\'' . $paginator->getPageName() . '\')'"
                                icon="heroicon-s-chevron-right"
                                aria-label="{{ __('tables::table.pagination.buttons.next.label') }}"
                                :disabled="! $paginator->hasMorePages()"
                                rel="next"
                            />
                        </ol>
                    </div>
                @endif
            @endif
        </div>
    </div>
</nav>
