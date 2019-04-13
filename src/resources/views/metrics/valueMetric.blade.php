<div class="thrust-panel thrust-value-metric m2">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <h4 class="ml3 lighter-gray mb-2">{{ $metric->getTitle() }}</h4>
    <div class="ml3" style="height:106px;">
        <h1 style="font-size:40px">{{ $metric->applyFormat($metric->getResult()) }}</h1>
    </div>
    <div class="ml3 mb1 {{ $metric->getIncreasePercentage() > 0 ? "green" : "red" }}">
        @if ($metric->getIncreasePercentage() > 0 )
            @icon(arrow-up)
        @else
            @icon(arrow-down)
        @endif
        {{ number_format($metric->getIncreasePercentage(), 2) }}%
        {{$metric->getIncreasePercentage() > 0 ? "Increase" : "Decrease"}}
    </div>
</div>