    @if ($fullPage)
        <h2> {{  ucFirst(str_singular($resourceName)) }} </h2>
    @else
        <div class="configForm">
            <h2> {{ $object->{$nameField} ?? 'New' }}</h2>
        </div>
    @endif

    @if (isset($object->id) )
        <form action="{{route('thrust.update', [$resourceName, $object->id] )}}" id='thrust-edit-form' method="POST">
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

    @if (isset($object->id) )
        <a class="secondary button hidden" id="thrust-save-and-continue" onclick="submitAjaxForm('thrust-edit-form')">{{ __("thrust::messages.saveAndContinueEditing") }}</a>
    @endif
</form>


@push('edit-scripts')
    @if (! $fullPage)
        @include('thrust::components.js.saveAndContinue')
    @endif
    <script>
        // $('#popup > select > .searchable').select2({ width: '325', dropdownAutoWidth : true });
        $('.searchable').select2({
            width: '300px',
            dropdownAutoWidth : true,
            dropdownParent: $('#popup')
        });
        setupVisibility({!! json_encode($visibility)  !!});
    </script>
@endpush

@if(!$fullPage)
    @stack('edit-scripts')
@endif