@extends(config('thrust.indexLayout'))
@section('content')
    <div class="thrust-index-header description">
        <span class="thrust-index-title title">
            @if (isset($parent_id) )
                @php $parent = $resource->parent($parent_id) @endphp
                @if($isChild)
                    <a href="{{ route('thrust.hasMany', $hasManyBackUrlParams) }}"> {{ \BadChoice\Thrust\Facades\Thrust::make(app(\BadChoice\Thrust\ResourceManager::class)->resourceNameFromModel($parent))->parent($parent)->name }} </a>
                @elseif (in_array(\BadChoice\Thrust\Contracts\CustomBackRoute::class,class_implements(get_class($resource))))
                    <a href="{{ $resource->backRoute() }}"> {{ $resource->backRouteTitle() }} </a>
                @else
                    <a href="{{ route('thrust.index', [app(\BadChoice\Thrust\ResourceManager::class)->resourceNameFromModel($parent) ]) }}"> {{ trans_choice(config('thrust.translationsPrefix') . Illuminate\Support\Str::singular(app(\BadChoice\Thrust\ResourceManager::class)->resourceNameFromModel($parent)), 2) }} </a>
                @endif
                 / {{ $parent->name }} -
            @endif
            {{ trans_choice(config('thrust.translationsPrefix') . Illuminate\Support\Str::singular($resourceName), 2) }}
            ({{ $resource->rows()->total() }})
        </span>
        <br><br>
        @include('thrust::components.mainActions')
        <div class="thrust-title-description">
            {!! $description ?? "" !!}
        </div>

        @include('thrust::components.search')
        <div class="pb1 text-right thrust-actions">
            @include('thrust::components.filters')
            @include('thrust::components.actions')
        </div>

    </div>


    <div id="all" @if(request('search')) style="display: none;" @endif>
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
