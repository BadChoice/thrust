<div id="{{$metric->uriKey()}}-div" class="{{$metric->getSize()}}">
    <div class="thrust-panel thrust-trend-metric m2" style="width:402px; height:175px">
        <h4 class="ml2 lighter-gray">{{ $metric->getTitle() }}</h4><br>
        <div class="text-center">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
        </div>
    </div>
</div>

@push('edit-scripts')
    <script>
        $('#{{$metric->uriKey()}}-div').load("{{route('thrust.metric', base64_encode(get_class($metric)))}}?metricRange={{request('metricRange')}}")
    </script>
@endpush