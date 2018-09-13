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

    @if ($searchable)
        @include('thrust::components.search')
    @endif
    @if (count($resource->actions()) > 0)
    <div class="pt4 pb1 text-right">
        @foreach($resource->actions() as $action)
            <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", {{$action->needsConfirmation}}, "{{$action->confirmationMessage}}")'>{!! $action->getTitle() !!}</button>
        @endforeach
    </div>
    @endif
    <div id="all">
        {!! (new BadChoice\Thrust\Html\Index($resource))->show() !!}
    </div>
    <div id="results"></div>
@stop

@section('scripts')
    @parent
    @if ($searchable)
        @include('thrust::components.searchScript', ['resourceName' => $resourceName])
    @endif
    @include('thrust::components.js.actions', ['resourceName' => $resourceName]);

@stop