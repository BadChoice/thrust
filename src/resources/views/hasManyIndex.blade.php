@extends(config('thrust.indexLayout'))
@section('content')
    <div class="description">
        <span class="title">
            {{ trans_choice( config('thrust.translationsPrefix') . str_singular($resourceName), 2) }}
            ({{ $resource->count() }})
        </span>
        <br><br>
        <div class="actions">
            @foreach($resource->mainActions() as $action)
                {!! $action->display($resourceName) !!}
            @endforeach
        </div>
        {{ trans_choice( config('thrust.translationsDescriptionsPrefix') . str_singular($resourceName), 1) }}
    </div>

    <div id="all">
        {!! (new BadChoice\Thrust\Html\Index($resource))->show() !!}
    </div>
    <div id="results"></div>
@stop

@section('scripts')
    @parent
@stop