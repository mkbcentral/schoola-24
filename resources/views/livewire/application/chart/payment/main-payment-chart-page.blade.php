<div>
    <div id="chart"></div>

    @push('js')
        <script type="module">
            var options = {
                series: [{
                    name: 'Frais',
                    type: 'area',
                    data: @json($dataSeries)
                }, ],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    curve: 'smooth'
                },
                fill: {
                    type: 'solid',
                    opacity: [0.35, 1],
                },
                labels: @json($labels),
                markers: {
                    size: 0
                },
                yaxis: [{
                    title: {
                        text: 'Montant',
                    },
                }, ],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0) + " USD";
                            }
                            return y;
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            document.addEventListener('livewire:load', () => {
                $this.on('refreshChart', () => {
                    console.log("L'événement 'refreshChart' a été émis et capturé en JavaScript.");
                    // Logique de rafraîchissement du graphique
                    refreshChart();
                });
            });
            chart.updateSeries([{
                name: 'Frais',
                type: 'area',
                data: @json($dataSeries)
            }])
        </script>
    @endpush
</div>
