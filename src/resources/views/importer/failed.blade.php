@extends(config('thrust.indexLayout'))
@section('content')
    <div class="thrust-index-header description">
        <span class="thrust-index-title title">
            <a href="{{route('thrust.index', $resourceName) }}">{{ $resource->getTitle() }}</a> / {{  __('thrust::import') }}
        </span>
    </div>

    <div class="ml-4 p-4 bg-white shadow mt-8">
        <div class="font-lg mb-8">{{ __('thrust::importFailed') }}</div>
        @if (isset($exception->validator))
            @foreach($exception->validator->errors()->getMessages() as $field => $messages)
                <div>
                    <ul>
                    {{ $field }}
                    @foreach($messages as $messsage)
                        <li> · {{ $messsage }}</li>
                    @endforeach
                    </ul>
                </div>
            @endforeach
        @else
            {{ $exception->getMessage() }}
        @endif
        <div class="mt-8">
        <a href="{{route('thrust.import', $resourceName)}}"> {{__('thrust::retryImport') }} </a>
        </div>
    </div>
@stop