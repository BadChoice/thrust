
<div class="label">{{ $title }}</div>
<div class="field">
    <input id="{{$field}}" name="{{$field}}">
</div>
<script>
    new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $id, $relationship]) }}')
        .show('#{{$field}}')
        .initSelection("1", "Spiderman");
</script>