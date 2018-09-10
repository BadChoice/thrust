<div class="configForm">
    <h2> {{ $object->{$nameField} ?? 'New' }}</h2>
</div>

    @if (isset($object->id))
        <form action="{{route('thrust.update', [$resourceName, $object->id] )}}" method="POST">
        {{ method_field('PUT') }}
    @else
        <form action="{{route('thrust.store', [$resourceName] )}}" method="POST">
    @endif
    {{ csrf_field() }}
    <div class="configForm">
        @foreach($fields as $field)
            {!! $field->displayInEdit($object) !!}
        @endforeach
    </div>

    <button> {{ __("thrust::messages.save") }} </button>

</form>

<script>
    // $('#popup > select > .searchable').select2({ width: '325', dropdownAutoWidth : true });
    $('.searchable').select2({
        width: '300px',
        dropdownAutoWidth : true,
        dropdownParent: $('#popup')
    });
</script>