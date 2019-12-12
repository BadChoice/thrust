@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <template id="template-{{$field}}">
        <div id="keyValue-template" class="mb2 keyValueField-{{$field}}" style="height:30px">
            <div class="inline" id="keyValueFields-template">
                {!! $keyValueField->generateKeyField('template') !!}
                {!! $keyValueField->generateValueField('template') !!}
            </div>
            <span>
                <a class="button secondary" onclick="keyValueRemove('template')">@icon(times)</a>
            </span>
        </div>
    </template>

    <div id="keyValue-{{$field}}">
        @if (! empty($value))
            @foreach($value as $v)
                <div id="keyValue-{{$loop->iteration}}" class="mb2 keyValueField-{{$field}}" style="height:30px">
                    <div class="inline" id="keyValueFields-{{$loop->iteration}}">
                        {!! $keyValueField->generateKeyField($loop->iteration, $v->key) !!}
                        {!! $keyValueField->generateValueField($loop->iteration, $v->value) !!}
                    </div>
                    <span>
                        <a class="button secondary" onclick="keyValueRemove('{{$loop->iteration}}')">@icon(times)</a>
                    </span>
                </div>
            @endforeach
        @endif
    </div>
    <div>
        <a class="button secondary" onclick="keyValueAdd()" class="pointer"> @icon(plus) {{ __('admin.add') }}</a>
    </div>

    @push('edit-scripts')
        <script>
            function keyValueRemove(iteration){
                $("#keyValue-" + iteration).hide();
                $("#keyValueFields-" + iteration).remove();
            }

            function keyValueAdd(){
                var template    = $("#template-{{$field}}").html();
                var newKeyValue = $(template);

                let n = 100 + Math.floor(Math.random() * 50);

                newKeyValue.prop('id', 'keyValue-' + n);
                newKeyValue.find('div').first('div').prop('id', 'keyValueFields-' + n);

                newKeyValue.find('div').first('div').find('input').first().prop('id', 'keyValue['+ n +'][key]');
                newKeyValue.find('div').first('div').find('input').first().prop('name', 'keyValue['+ n +'][key]');

                newKeyValue.find('div').first('div').find('input').last().prop('id', 'keyValue['+ n +'][value]');
                newKeyValue.find('div').first('div').find('input').last().prop('name', 'keyValue['+ n +'][value]');
                $('#keyValue-{{$field}}').append(newKeyValue);
            }
        </script>
    @endpush
@endcomponent