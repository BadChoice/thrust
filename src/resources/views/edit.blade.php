    @if ($fullPage)
        <h2> {{  trans_choice(config('thrust.translationsPrefix'). str_singular($resourceName), 1) }} </h2>
    @else
        <div class="configForm">
            <h2> {{ $object->{$nameField} ?? 'New' }}</h2>
        </div>
    @endif

    @if (isset($object->id) )
        <form action="{{route('thrust.update', [$resourceName, $object->id] )}}" id='thrust-form-{{$object->id}}' method="POST">
        {{ method_field('PUT') }}
    @else
        <form action="{{route('thrust.store', [$resourceName] )}}" method="POST">
    @endif
    {{ csrf_field() }}
    <div class="thrust-tabs"><li id="thrust-tabs-list"></li></div>
    <div class="configForm">
        @foreach($fields as $field)
            {!! $field->displayInEdit($object) !!}
        @endforeach
    </div>

    @include('thrust::components.saveButton')

    @if (isset($object->id) )
        <a class="secondary button hidden" id="thrust-save-and-continue" onclick="submitAjaxForm('thrust-form-{{$object->id}}')">{{ __("thrust::messages.saveAndContinueEditing") }}</a>
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
            @if (! $fullPage) dropdownParent: $('{{config('thrust.popupId', '#popup')}}') @endif
        });
        setupVisibility({!! json_encode($visibility)  !!});
    </script>

    <script>
        $(".formTab").each(function(idx, element){
            $("#thrust-tabs-list").append("<ul class='thrust-tab-header' onclick='showTab(this, \"#" + $(element).attr("id") +"\")'>" + $(element).attr('title') + "</ul>")
        });
        $(".thrust-tab-header").first().addClass('active');
        $(".formTab").first().addClass('active');

        function showTab(header, element){
            $(".formTab").hide();
            $(element).show();
            $('.thrust-tab-header').removeClass('active');
            $(header).addClass('active')
        }
    </script>
@endpush

@if(!$fullPage)
    @stack('edit-scripts')
@endif