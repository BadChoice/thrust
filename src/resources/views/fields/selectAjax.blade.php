@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null])
    <select id="{{$field}}" name="{{$field}}">
        <option value="{{$value}}" selected>{{$name}}</option>
    </select>

    @push('edit-scripts')
        <script>
            new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $id, $relationship]) }}?allowNull={{$allowNull}}',{
                dropdownParent: $('#popup'),
            }).show('#{{$field}}');
        </script>
    @endpush
@endcomponent