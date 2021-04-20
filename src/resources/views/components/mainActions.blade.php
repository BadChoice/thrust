<div class="actions thrust-main-actions">
    <?php
        $mainActions = collect($resource->mainActions());
        if ($resource->sortableIsActive()) {
            $mainActions->prepend(BadChoice\Thrust\Actions\SaveOrder::make('saveOrder'));
        }
        if (request('sort')) {
            $mainActions->prepend(BadChoice\Thrust\Actions\ClearSorting::make('clearSorting'));
        }
    ?>
    @foreach($mainActions as $action)
        {!! $action->display($resourceName, $parent_id ?? null) !!}
    @endforeach
</div>