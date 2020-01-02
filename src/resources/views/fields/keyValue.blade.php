@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <input type="hidden" name="{{$field}}" value="">
    <template id="template-{{$field}}">
        <div id="keyValue-template" class="mb2 keyValueField-{{$field}}" style="height:30px">
            <div class="inline" id="keyValueFields-template">
                <div class="inline" id="key">
                    {!! $keyValueField->generateKeyField('template') !!}
                </div>
                <div class="inline" id="value">
                    {!! $keyValueField->generateValueField('template') !!}
                </div>
            </div>
            <span>
                <a class="button secondary" onclick="keyValueRemove(this)">@icon(times)</a>
            </span>
        </div>
    </template>

    <div id="keyValue-{{$field}}">
        @if (! empty($value))
            @foreach($value as $v)
                <div id="keyValue-{{$loop->iteration}}" class="mb2 keyValueField-{{$field}}" style="height:30px">
                    <div class="inline" id="keyValueFields-{{$loop->iteration}}">
                        <div class="inline" id="key">
                            {!! $keyValueField->generateKeyField($loop->iteration, $v->key) !!}
                        </div>
                        <div class="inline" id="value">
                            {!! $keyValueField->generateValueField($loop->iteration, $v->value) !!}
                        </div>
                    </div>
                    <span>
                        <a class="button secondary" onclick="keyValueRemove(this)">@icon(times)</a>
                    </span>
                </div>
            @endforeach
        @endif
    </div>
    <div>
        <a class="button secondary" onclick="keyValueAdd('{{$field}}')" class="pointer"> @icon(plus) {{ __('admin.add') }}</a>
    </div>

    @push('edit-scripts')
        <script>
            function keyValueRemove(element){
                $(element).parent().parent().find('div').remove();
                $(element).parent().parent().hide();
            }

            function keyValueAdd(fieldName){
                var template    = $("#template-"+fieldName).html();
                var newKeyValue = $(template);

                let n = 100 + Math.floor(Math.random() * 50);

                newKeyValue.prop('id', 'keyValue-' + n);
                newKeyValue.find('div').first('div').prop('id', 'keyValueFields-' + n);

                newKeyValue.find('div').first('div').find('div').first().find('input').first().prop('id', fieldName + '['+ n +'][key]');
                newKeyValue.find('div').first('div').find('div').first().find('input').first().prop('name', fieldName + '['+ n +'][key]');
                newKeyValue.find('div').first('div').find('div').first().find('select').first().prop('id', fieldName + '['+ n +'][key]');
                newKeyValue.find('div').first('div').find('div').first().find('select').first().prop('name', fieldName + '['+ n +'][key]');

                newKeyValue.find('div').first('div').find('div').last().find('input').last().prop('id', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('input').last().prop('name', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('select').last().prop('id', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('select').last().prop('name', fieldName + '['+ n +'][value]');
                $('#keyValue-' + fieldName).append(newKeyValue);
            }
        </script>
    @endpush
@endcomponent