@extends(config('thrust.indexLayout'))
@section('content')
    <div class="description">
        <span class="title">
            @if (isset($parent_id) )
                @php $parent = $resource->parent($parent_id) @endphp
                <a href="{{route('thrust.index', [app(\BadChoice\Thrust\ResourceManager::class)->resourceNameFromModel($parent) ]) }}">{{ $parent->name }} </a> /
            @endif
            {{ trans_choice(config('thrust.translationsPrefix') . str_singular($resourceName), 2) }}
            ({{ $resource->count() }})
        </span>
        <br><br>
        <div class="actions">
            @foreach($resource->mainActions() as $action)
                {!! $action->display($resourceName, $parent_id ?? null) !!}
            @endforeach
        </div>
        {{ $description }}

        @include('thrust::components.search')
        <div class="pb1 text-right" style="margin-top:-29px; margin-right: -8px;">
            @include('thrust::components.filters')
            @include('thrust::components.actions')
        </div>

    </div>

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
    @include('thrust::components.js.filters', ['resourceName' => $resourceName]);
    @include('thrust::components.js.editInline', ['resourceName' => $resourceName]);

@stop