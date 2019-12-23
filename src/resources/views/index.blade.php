@extends(config('thrust.indexLayout'))
@section('content')
    <div class="thrust-index-header description">
        <span class="thrust-index-title title">
            @if (isset($parent_id) )
                @php $parent = $resource->parent($parent_id) @endphp
                <a href="{{route('thrust.index', [app(\BadChoice\Thrust\ResourceManager::class)->resourceNameFromModel($parent) ]) }}">{{ $parent->name }} </a> /
            @endif
            {{ trans_choice(config('thrust.translationsPrefix') . str_singular($resourceName), 2) }}
            ({{ $resource->rows()->total() }})
        </span>
        <br><br>
        @include('thrust::components.mainActions')
        {!! $description ?? "" !!}

        @include('thrust::components.search')
        <div class="pb1 text-right thrust-actions">
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
    @include('thrust::components.js.actions', ['resourceName' => $resourceName])
    @include('thrust::components.js.filters', ['resourceName' => $resourceName])
    @include('thrust::components.js.editInline', ['resourceName' => $resourceName])

@stop