@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <select id="{{$field}}" name="{{$field}}">
        <option value="{{$value}}" selected>{{$name}}</option>
    </select>

    @push('edit-scripts')
        <script>
            new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $id ?? 0, $relationship]) }}?allowNull={{$allowNull}}',{
                @if (isset($fullPage) && ! $fullPage) dropdownParent: $('{{config('thrust.popupId', '#popup')}}') @endif
            }).show('#{{$field}}');
        </script>
    @endpush
@endcomponent