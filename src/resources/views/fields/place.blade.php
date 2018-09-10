<div id="{{$field}}_div">
    <div class="label">{{ $title }}</div>
    <div class="field" style="width:300px">
        <input type="text" name="{{$field}}" id="{{$field}}" placeholder="{{$title}}" value="{{$value}}"
        style="width:300px; border: 1px solid #c8c8c8; color: #32393F; height: 26px; border-radius: 4px; padding-left: 12px; font-size:10px"/>
    </div>
</div>
<script>
    var placesAutocomplete = places({
        container: document.querySelector('#{{$field}}'),
        type : "{{$type}}",
        style : true,   //To use the algolia style or not
        templates: {
            value: function(suggestion) {
                return suggestion.name;
            }
        }
    });

    placesAutocomplete.on('change', function resultSelected(e) {
        document.querySelector('#{{$field}}').value = e.suggestion.name || '';
        document.querySelector('#{{$relatedFields["city"]}}').value = e.suggestion.city || '';
        document.querySelector('#{{$relatedFields["postalCode"]}}').value = e.suggestion.postcode || '';
        document.querySelector('#{{$relatedFields["state"]}}').value = e.suggestion.administrative || '';
        document.querySelector('#{{$relatedFields["country"]}}').value = e.suggestion.country || '';
    });
</script>
