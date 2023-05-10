@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <input type="hidden" name="{{$field}}" value="">
    <script type="template/html" id="template-{{$field}}">
{{--    <template id="template-{{$field}}">--}}
        <div id="keyValue-template" class="mb2 keyValueField-{{$field}}" style="height:auto">
            <div class="inline" id="keyValueFields-template">
                <div class="inline" id="range">
                    <input type='time' id='{{$field.'[template][null]'}}' value='' name='{{$field.'[template][null]'}}' placeholder='start' style='width:132px'>
                    <input type='time' id='{{$field.'[template][null]'}}' value='' name='{{$field.'[template][null]'}}' placeholder='end' style='width:132px'>
                </div>
                <div class="inline" id="value">
                    @if(! $keyValueField->valueOptions)
                        <input type='text' id='{{$field.'[template][null]'}}' value='' name='{{$field.'[template][null]'}}' placeholder='value' style='width:132px'>
                    @else
                        <select @if($searchable) class="searchable" @endif @if($multiple) multiple @endif id='{{$field.'[template][null]'}}' name='{{$field.'[template][null]'}}' style='width:132px'>{!! $keyValueField->generateOptions($keyValueField->valueOptions, null) !!}</select>
                    @endif
                </div>
            </div>
            <span>
                <a class="button secondary" onclick="keyValueRemove(this)">@icon(times)</a>
            </span>
        </div>
{{--    </template>--}}
    </script>
    <div id="keyValue-{{$field}}">
        @if (! empty($value))
            @foreach($value as $v)
                <div id="keyValue-{{$loop->iteration}}" class="mb2 keyValueField-{{$field}}" style="height:auto">
                    <div class="inline" id="keyValueFields-{{$loop->iteration}}">
                        <div class="inline" id="start">
                            <input @if($fixed) readonly="readonly" @endif type='time' id='{{$field.'['.$loop->iteration.'][start]'}}' value='{{$v->start}}' name='{{$field.'['.$loop->iteration.'][start]'}}' placeholder='start' style='width:132px'>
                        </div>
                        <div class="inline" id="end">
                            <input @if($fixed) readonly="readonly" @endif type='time' id='{{$field.'['.$loop->iteration.'][end]'}}' value='{{$v->end}}' name='{{$field.'['.$loop->iteration.'][end]'}}' placeholder='end' style='width:132px'>
                        </div>
                        <div class="inline" id="value">
                            @if(! $keyValueField->valueOptions)
                                <input type='text' id='{{$field.'['.$loop->iteration.'][value]'}}' value='{{$v->value}}' name='{{$field.'['.$loop->iteration.'][value]'}}' placeholder='value' style='width:132px'>
                            @else
                                <select @if($searchable) class="searchable" @endif @if($multiple) multiple @endif id='{{$field.'['.$loop->iteration.'][value]'}}' name='{{$field.'['.$loop->iteration.'][value]'}}@if($multiple)[]@endif' style='width:132px'>{!! $keyValueField->generateOptions($keyValueField->valueOptions, $v->value) !!}</select>
                            @endif
                        </div>
                    </div>
                    @if(!$fixed)
                        <span>
                            <a class="button secondary" onclick="keyValueRemove(this)">@icon(times)</a>
                        </span>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
    @if(! $fixed)
        <div>
            <a class="button secondary" onclick="keyValueAdd('{{$field}}')" class="pointer"> @icon(plus) {{ __('admin.add') }}</a>
        </div>
    @endif

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

                newKeyValue.find('div').first('div').find('div').first().find('input').first().prop('id', fieldName + '['+ n +'][start]');
                newKeyValue.find('div').first('div').find('div').first().find('input').first().prop('name', fieldName + '['+ n +'][start]');
                newKeyValue.find('div').first('div').find('div').first().find('input').last().prop('id', fieldName + '['+ n +'][end]');
                newKeyValue.find('div').first('div').find('div').first().find('input').last().prop('name', fieldName + '['+ n +'][end]');

                newKeyValue.find('div').first('div').find('div').last().find('input').last().prop('id', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('input').last().prop('name', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('select').last().prop('id', fieldName + '['+ n +'][value]');
                newKeyValue.find('div').first('div').find('div').last().find('select').last().prop('name', fieldName + '['+ n +'][value]');
                $('#keyValue-' + fieldName).append(newKeyValue);

                initSelect2()
            }
        </script>
    @endpush
@endcomponent
