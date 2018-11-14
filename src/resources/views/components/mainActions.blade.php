<div class="actions thrust-main-actions">
    <?php
        $mainActions = collect($resource->mainActions());
        if ($resource::$sortable) {
            $mainActions->prepend(BadChoice\Thrust\Actions\SaveOrder::make('saveOrder'));
        }
    ?>
    @foreach($mainActions as $action)
        {!! $action->display($resourceName, $parent_id ?? null) !!}
    @endforeach
</div>