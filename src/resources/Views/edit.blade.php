<div class="configForm">
    <h2> {{ $object->{$nameField} }}</h2>
</div>

<form action="{{route('thrust.update', [$resourceName, $object->id] )}}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="configForm">
        @foreach($fields as $field)
            {!! $field->displayInEdit($object) !!}
        @endforeach
    </div>

    <button> {{ __("thrust::messages.save") }} </button>

</form>