@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null])
    <div class="places" style="width:300px">
        <input type="text" name="{{$field}}" id="{{$field}}" placeholder="{{$title}}" value="{{$value}}"  {!! $validationRules !!} 
        style="width:300px; border: 1px solid #c8c8c8; color: #32393F; height: 26px; border-radius: 4px; padding-left: 12px; font-size:10px" autocomplete="off"/>
    </div>

    @push('edit-scripts')
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
            @if($relatedFields["city"])
                document.querySelector('#{{$relatedFields["city"]}}').value = e.suggestion.city || '';
            @endif
            @if($relatedFields["postalCode"])
                document.querySelector('#{{$relatedFields["postalCode"]}}').value = e.suggestion.postcode || '';
            @endif
            @if($relatedFields["state"])
                document.querySelector('#{{$relatedFields["state"]}}').value = e.suggestion.administrative || '';
            @endif
            @if($relatedFields["country"])
                document.querySelector('#{{$relatedFields["country"]}}').value = e.suggestion.country || '';
            @endif
        });
    </script>
    @endpush
@endcomponent
