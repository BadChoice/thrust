@extends(config('thrust.indexLayout'))
@section('content')
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