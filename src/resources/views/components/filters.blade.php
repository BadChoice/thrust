<?php $filters = $resource->filters() ?>
@if ($filters && count($filters) > 0)
    <div x-data = "{isOpen : false}">
        <button class="secondary relative" x-on:click="isOpen = !isOpen"> @icon(filter) @icon(caret-down)</button>
        <?php $filtersApplied = $resource->filtersApplied(); ?>
        <div class="thrust-filters-dropdown absolute" x-show="isOpen" x-transition x-cloak x-on:click.away = "isOpen = false">
            <form id="filtersForm" class="thrust-filters-form">
            @foreach (collect($filters) as $filter)
                <div>
                    <div> {!! $filter->getIcon() !!} {!! $filter->getTitle() !!}</div>
                    <div class="text-left">
                        {!! $filter->display($filtersApplied) !!}
                    </div>
                </div>
            @endforeach
            <button class="secondary">{{ __("thrust::messages.apply") }}</button>
            </form>
        </div>
    </div>
@endif