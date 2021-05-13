@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    @php $id = str_replace("]", "", str_replace("[", "", $field)) @endphp
    <div class="text-right mt2 mb3" style="max-width:300px">
        @foreach ($languages as $language)
            <a class="secondary p2 pointer uppercase circle languageSelector{{$id}}" id="{{$id}}languageSelector{{$language}}" onclick="changeLanguage('{{$id}}','{{$language}}')">{{ $language }}</a>
        @endforeach
    </div>

    @foreach ($languages as $language)
        @if( $isTextArea )
            <textarea id="{{$id}}{{$language}}" name="{{$field}}[{{$language}}]" placeholder="{{$title}}" {{$attributes}} {!! $validationRules !!} @if($inline)
            style="width:auto;" @endif class="hidden languageField{{$id}}">{{$value[$language] ?? ""}}</textarea>
        @else
            <input type={{$type}} id="{{$id}}{{$language}}" value="{{$value[$language] ?? ""}}" name="{{$field}}[{{$language}}]" placeholder="{{$title}}"
                   {{$attributes}} {!! $validationRules !!} @if($inline) style="width:auto;" @endif class="hidden languageField{{$id}}">
        @endif
    @endforeach

    @push('edit-scripts')
        <script>
            changeLanguage("{{$id}}", "{{$languages[0]}}");
            function changeLanguage(field, language) {
                $('.languageSelector'+field).css("background-color","#fff");
                $('#'+field+'languageSelector'+language).css("background-color","#eee");
                $('.languageField' + field).hide();
                $('#'+field+language).removeClass('hidden').show();
            }
        </script>
    @endpush
@endcomponent