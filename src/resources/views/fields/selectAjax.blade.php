
<div class="label">{{ $title }}</div>
<div class="field">
    <select id="{{$field}}" name="{{$field}}">
        <option value="{{$value}}" selected>{{$name}}</option>
    </select>
</div>
<script>
    new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $id, $relationship]) }}',{
        dropdownParent: $('#popup')
    }).show('#{{$field}}');
</script>