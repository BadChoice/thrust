@extends(config('thrust.indexLayout'))
@section('content')
    <div class="thrust-index-header description">
        <span class="thrust-index-title title">
            <a href="{{route('thrust.index', $resourceName) }}">{{ $resource->getTitle() }}</a> / {{  __('thrust::import') }}
        </span>
    </div>

    <div class="m-4 bg-white p-4 shadow">
        <form method="post" action="{{route('thrust.doImport', $resourceName)}}">
        @csrf
       <input type="hidden" name="csv" value="{{$importer->csv}}">
        @foreach($resource->fieldsFlattened()->filter(fn($field) => $field->importable) as $thrustField)
           <div class="flex-row space-y-4">
               <select name="mapping[{{$thrustField->field}}]"
                       placeholder="{{ __('thrust::pickAColumn') }}"
                       @if ($thrustField->isRequired()) required @endif
               >
                   <option value="">--</option>
                   @foreach($importer->fields() as $csvField)
                       <option value="{{$csvField}}">{{$csvField}}</option>
                   @endforeach
               </select>
               @icon(arrow-right)
               <div class="inline bg-gray-100 rounded-lg px-2 py-2 w-48" >
                   {{ $thrustField->getTitle() }}
                   @if ($thrustField->isRequired()) * @endif
               </div>
           </div>
           @endforeach
            <div class="mt-4">
                <button class="button"> {{ __('thrust::import') }}</button>
            </div>
        </form>
    </div>

@stop