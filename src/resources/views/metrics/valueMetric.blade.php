<div class="thrust-panel thrust-value-metric m2">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <h4 class="ml2 lighter-gray mb-2">{{ $metric->getTitle() }}</h4>
    <div class="ml2" style="height:130px;">
        <h1 style="font-size:40px">{{ $metric->getResult() }}</h1>
    </div>
</div>