{{--
    https://www.webdew.com/blog/google-places-autocomplete-implementation
--}}
@component('thrust::components.formField', ['field' => $field, 'title' => $title, 'description' => $description ?? null])
    <div class="places" style="width:300px">
        <input type="text" name="{{$field}}" id="{{$field}}" placeholder="{{$title}}" value="{{$value}}"  {!! $validationRules !!} 
        style="width:300px; border: 1px solid #c8c8c8; color: #32393F; height: 26px; border-radius: 4px; padding-left: 12px; font-size:10px" autocomplete="off"/>
    </div>

    @push('edit-scripts')
    <script>
        const gmFindAddressComponent = (place, type) => place.address_components.find(o => o.types.indexOf(type) !== -1)?.long_name || '';
        const gmAutocomplete = new google.maps.places.Autocomplete(document.getElementById('{{$field}}'));
        gmAutocomplete.addListener('place_changed', () => {
            const place = gmAutocomplete.getPlace();
            const street = gmFindAddressComponent(place, 'route') || gmFindAddressComponent(place, 'premise');
            const number = gmFindAddressComponent(place, 'street_number');
            document.getElementById('{{$field}}').value = number ? `${street}, ${number}` : street;
            @if($relatedFields['city'])
                document.getElementById('{{$relatedFields['city']}}').value = gmFindAddressComponent(place, 'locality');
            @endif
            @if($relatedFields['postalCode'])
                document.getElementById('{{$relatedFields['postalCode']}}').value = gmFindAddressComponent(place, 'postal_code');
            @endif
            @if($relatedFields['state'])
                document.getElementById('{{$relatedFields['state']}}').value = gmFindAddressComponent(place, 'administrative_area_level_2');
            @endif
            @if($relatedFields['country'])
                document.getElementById('{{$relatedFields['country']}}').value = gmFindAddressComponent(place, 'country');
            @endif
        });
    </script>
    @endpush
@endcomponent
