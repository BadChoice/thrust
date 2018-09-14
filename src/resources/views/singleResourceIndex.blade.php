@extends(config('thrust.indexLayout'))
@section('content')
    <div class="description mb4">
        {!! ( new BadChoice\Thrust\Html\Edit($resource))->show($object, true)  !!}
    </div>
@stop
