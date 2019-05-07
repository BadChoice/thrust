<h2> {{$title}} </h2>

@include('thrust::components.searchPopup')

@if($sortable)
    <div class="actions">
        <button class="secondary" onclick="saveChildOrder('{{$resourceName}}','{{$object->id}}', '{{$belongsToManyField->field}}')">@icon(sort) {{__('thrust::messages.saveOrder')}}</button>
    </div>
@endif
<div id="popup-all">
    @include('thrust::belongsToManyTable')
</div>
<div id="popup-results"></div>

<div class="mt4">
    <form id='belongsToManyForm' action="{{route('thrust.belongsToMany.store', [$resourceName, $object->id, $belongsToManyField->field]) }}" method="POST">
        {{ csrf_field() }}
        <select id="id" name="id" @if($belongsToManyField->searchable) class="searchable" @endif >
            @if (!$ajaxSearch)
                @foreach($belongsToManyField->getOptions($object) as $possible)
                    <option value='{{$possible->id}}'> {{ $possible->name }} </option>
                @endforeach
            @endif
        </select>
        @foreach($belongsToManyField->pivotFields as $field)
            {!! $field->displayInEdit(null, true)  !!}
        @endforeach
        <button class="secondary">{{__('thrust::messages.add') }} </button>
    </form>
</div>

<script>
    addListeners();
    // $('#popup > select > .searchable').select2({ width: '325', dropdownAutoWidth : true });
    @if ($searchable && !$ajaxSearch)
    $('.searchable').select2({
        width: '300px',
        dropdownAutoWidth : true,
        dropdownParent: $('{{config('thrust.popupId', '#popup')}}'),
    });
    @endif

    $('#popup-searcher').searcher('/thrust/{{$resourceName}}/{{$object->id}}/belongsToMany/{{$belongsToManyField->field}}/search/', {
        'resultsDiv' : 'popup-results',
        'allDiv' : 'popup-all',
        'updateAddressBar' : false,
        'onFound' : function(){
            addListeners();
        }
    });

    @if ($ajaxSearch)
    new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $object->id, $belongsToManyField->field]) }}?allowNull=0&allowDuplicates={{$allowDuplicates}}',{
        dropdownParent: $('{{config('thrust.popupId', '#popup')}}'),
    }).show('#id');
    @endif

    popupUrl = "{{route('thrust.belongsToMany', [$resourceName, $object->id, $belongsToManyField->field]) }}";
    $('#belongsToManyForm').on('submit', function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize()).done(function(){
            loadPopup(popupUrl)
        });
    });

    $("{{config('thrust.popupId', '#popup')}} .delete-resource").each(function(index, el){
        $(el).attr({
            'data-delete' : $(el).attr('data-delete') + ' ajax '
        });
    });

    $("{{config('thrust.popupId', '#popup')}} .sortableChild" ).sortable({
        axis: "y",
    });

</script>