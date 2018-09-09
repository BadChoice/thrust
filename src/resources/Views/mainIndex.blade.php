@extends(config('thrust.indexLayout'))
@section('content')

    <div class="description">
        <span class="title">
            {{ $resourceName }}
        </span>
        <br><br>
        <div class="actions">
            @foreach($resource->actions() as $action)
                {!! $action->display($resourceName) !!}
            @endforeach
        </div>
    </div>

    @include('thrust::components.search')
    <div id="all">
        {!! (new BadChoice\Thrust\Html\Index($resource))->show() !!}
    </div>
    <div id="results"></div>
@stop

@section('scripts')
    @parent
    @include('thrust::components.searchScript', ['resourceName' => $resourceName])
@stop