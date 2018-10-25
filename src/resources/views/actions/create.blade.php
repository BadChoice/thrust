<div class="configForm">
    <h2> {!! $action->getTitle()  !!}</h2>
</div>

<form action="{{route('thrust.actions.perform', [$resourceName])}}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="ids" value="{{request('ids')}}">
    <input type="hidden" name="action" value="{{get_class($action)}}">
    <div class="configForm">
        @foreach($action->fields() as $field)
            {!! $field->displayInEdit(null) !!}
        @endforeach
    </div>

    <button> {{ __("thrust::messages.perform") }} </button>
</form>

<script>
    $('.searchable').select2({
        width: '300px',
        dropdownAutoWidth : true,
        dropdownParent: $('{{config('thrust.popupId', '#popup')}}')
    });
</script>
