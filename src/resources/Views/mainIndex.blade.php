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

        @include('thrust::components.search')
        @include('thrust::components.actions')

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

    <script>
        $("tr").on("dblclick", function(element){
            editInline($(this).attr('id').replace("sort_", ""));
        });

        function editInline(id){
            var url = "{{route('thrust.editInline', [$resourceName, 1])}}".replace("1", id);
            $('#sort_'+id).load(url);
        }

        function submitInlineForm(id){
            $('#sort_'+id).find("td input,td select").each(function() {
                $(this).attr("form", "thrust-form-" + id);
            });
            $("#thrust-form-"+id).submit();
        }
    </script>

@stop