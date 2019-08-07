@extends(config('thrust.indexLayout'))
@section('content')
    @include('thrust::components.searchSingle')

    <div class="description mb4">
        {!! ( new BadChoice\Thrust\Html\Edit($resource, $resourceName))->show($object, true)  !!}
    </div>
@stop

@section('scripts')
    @parent
    @include('thrust::components.js.searchSingle')
@stop
