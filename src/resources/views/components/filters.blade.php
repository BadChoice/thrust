<?php $filters = $resource->filters() ?>
@if ($filters && count($filters) > 0)
    <div class="dropdown inline">
        <button class="secondary"> @icon(filter) @icon(caret-down)</button>
    </div>
    <?php $filtersApplied = $resource->filtersApplied(); ?>
    <ul class="dropdown-container filters" style="right:70px; margin-top:0px">
        <form id="filtersForm">
        @foreach (collect($filters) as $filter)
            <li>{{ $filter->title() }}</li>
            <li class="text-left">
                <select name="{{ $filter->class() }}" title="{{$filter->title()}}">
                    <option value="--">--</option>
                    @foreach ($filter->options() as $key => $value)
                        <?php $selected = $filtersApplied->keys()->contains($filter->class()) &&
                                          $filtersApplied[$filter->class()] != "--" &&
                                          $filtersApplied[$filter->class()] == $value; ?>
                        <option value="{{$value}}" @if($selected) selected @endif>{{$key}} </option>
                    @endforeach
                </select>
            </li>
        @endforeach
            <div class="text-center mt3">
                <button class="secondary w100">{{ __("thrust::messages.apply") }}</button>
            </div>
        </form>
    </ul>
@endif