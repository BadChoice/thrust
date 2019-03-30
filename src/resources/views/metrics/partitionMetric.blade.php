<div class="thrust-panel thrust-partition-metric m2">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <h4 class="ml2 lighter-gray">{{ $metric->getTitle() }}</h4>
    <canvas id="{{$metric->uriKey()}}" width="400" height="100"></canvas>
    <script>
        new Chart('{{$metric->uriKey()}}', {
            type: 'pie',
            data: {
                labels: @json($metric->getResult()->keys()),
                datasets: [{
                    label: '{{ $metric->getTitle() }}',
                    data: @json($metric->getResult()->values()),
                    backgroundColor: @json($metric->getColors()),
                    borderColor: [
                        'RGBA(72, 152, 220, 1.00)',
                    ],
                    pointBorderColor : 'RGBA(72, 152, 220, 1.00)',
                    pointBackgroundColor :  'RGBA(72, 152, 220, 1.00)',
                    pointBorderWidth: 0,
                    pointHoverRadius: 5,
                    borderWidth: 2
                }]
            },
            options: {
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 0
                    }
                },
                responsive : true,
                legend: {
                    position : 'left',
                    display: true
                },
                scales: {
                    yAxes: [{
                        display : false,
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display:false
                        }
                    }],
                    xAxes: [{
                        display : false,
                        gridLines: {
                            display:false
                        }
                    }]

                }
            }
        });
    </script>
</div>