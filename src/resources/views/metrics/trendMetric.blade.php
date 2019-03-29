<div class="thrust-panel thrust-trend-metric">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <canvas id="trend-metric" width="400" height="400"></canvas>
    <script>
        new Chart('trend-metric', {
            type: 'line',
            data: {
                labels: @json($metric->getResult()->pluck('date')),
                datasets: [{
                    label: '{{ $metric->uriKey() }}',
                    data: @json($metric->getResult()->pluck('count')),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</div>