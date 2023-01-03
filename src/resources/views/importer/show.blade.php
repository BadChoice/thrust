@extends(config('thrust.indexLayout'))
@section('content')
    <div class="thrust-index-header description">
        <span class="thrust-index-title title">
            <a href="{{route('thrust.index', $resourceName) }}">{{ $resource->getTitle() }}</a> / {{  __('thrust::import') }}
        </span>
    </div>

    <div class="m-4 bg-white p-4 shadow">

        <div class="mb-4">
        {{ __('thrust::rowsToImport' )}}: <b>{{ count($importer->rows()) }}</b>
        </div>

        <form method="post" action="{{route('thrust.doImport', $resourceName)}}">
        @csrf
       <input type="hidden" name="csv" value="{{$importer->csv}}">
            @include('thrust::importer.fieldMapping', [
             'field' => 'id',
             'title' => 'Id',
             'required' => false,
             'csvFields' => $importer->fields(),
        ])

        @foreach($resource->fieldsFlattened()->filter(fn($field) => $field->importable) as $thrustField)
            @include('thrust::importer.fieldMapping', [
                'field' => $thrustField->field,
                'title' => $thrustField->getTitle(),
                'required' => $thrustField->isRequired(),
                'csvFields' => $importer->fields(),
            ])
           @endforeach
            <div class="mt-4">
                <button class="button"> {{ __('thrust::import') }}</button>
            </div>
        </form>
    </div>

@stop