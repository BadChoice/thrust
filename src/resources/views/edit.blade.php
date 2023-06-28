    @if ($fullPage)
	<h2>{{ $title }}</h2>
    @else
        <div class="configForm">
            <span> {{ $breadcrumbs }} </span>
            <h2> {{ $object->{$nameField} ?: __('thrust::messages.new') }} </h2>
        </div>
    @endif

    @if (isset($object->id) )
        <form action="{{route('thrust.update', [$resourceName, $object->id] )}}" id='thrust-form-{{$object->id}}' method="POST">
        {{ method_field('PUT') }}
    @else
        <form action="{{route($multiple ? 'thrust.store.multiple' : 'thrust.store', [$resourceName] )}}" method="POST">
    @endif
    {{ csrf_field() }}
    <div class="thrust-tabs"><ul id="thrust-tabs-list"></ul></div>
    <div class="configForm">
        @foreach($fields as $field)
            @if (! $field->shouldHide($object, 'edit') && $field->shouldShow($object, 'edit'))
                {!! $field->displayInEdit($object) !!}
            @endif
        @endforeach
        @includeWhen($multiple, 'thrust::quantityInput')
    </div>

    @if (app(BadChoice\Thrust\ResourceGate::class)->can($resourceName, 'update', $object))
        @include('thrust::components.saveButton', ["updateConfirmationMessage" => $updateConfirmationMessage])

        @if (isset($object->id) )
            <a class="secondary button hidden" id="thrust-save-and-continue" onclick="submitAjaxForm('thrust-form-{{$object->id}}')">{{ __("thrust::messages.saveAndContinueEditing") }}</a>
        @endif
    @endif

</form>


@push('edit-scripts')
    @if (! $fullPage)
        @include('thrust::components.js.saveAndContinue')
    @endif
    <script>
        $('.searchable').select2({
            width: '300px',
            dropdownAutoWidth : true,
            @if (! $fullPage) dropdownParent: $('{{config('thrust.popupId', '#popup')}}') @endif
        });
        function initSelect2(){
            $('.searchable').select2({
                width: '300px',
                dropdownAutoWidth : true,
                @if (! $fullPage) dropdownParent: $('{{config('thrust.popupId', '#popup')}}') @endif    
            });
        }
        setupVisibility({!! json_encode($hideVisibility)  !!}, {!! json_encode($showVisibility)  !!});
    </script>

    <script>
        Array.from(document.getElementsByClassName('formTab')).forEach(function(element){
            document.getElementById('thrust-tabs-list').insertAdjacentHTML('beforeend', "<li class='thrust-tab-header " + element.id + "' onclick='showTab(this, \"" + element.id +"\")'>" + element.title + "</li>")
        })

        document.getElementsByClassName('thrust-tab-header').item(0)?.classList?.add('active')
        document.getElementsByClassName('formTab').item(0)?.classList?.add('active')

        function showTab(header, tabId){
            const newTab = document.getElementById(tabId)
            const oldTab = document.getElementsByClassName('formTab active').item(0)

            oldTab.style.display = 'none'
            oldTab.classList.remove('active')
            newTab.style.display = 'block'
            newTab.classList.add('active')

            document.getElementsByClassName('thrust-tab-header active').item(0).classList.remove('active')
            header.classList.add('active')
        }

        Array.from(document.getElementsByTagName('input')).forEach(function(elem){
            elem.addEventListener('invalid', () => {
                const tab = elem.closest('.formTab')
                if (tab) {
                    showTab(document.getElementsByClassName('thrust-tab-header ' + tab.id).item(0), tab.id)
                }
            })
        })
    </script>
@endpush

@if(!$fullPage)
    @stack('edit-scripts')
@endif
