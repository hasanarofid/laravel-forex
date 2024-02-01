<canvas id="horizontalBarChart" width="800" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    
    <script>
        var labels = <?php echo json_encode($labels); ?>;
        var buyData = <?php echo json_encode($buyData); ?>;
        var sellData = <?php echo json_encode($sellData); ?>;
    
        var ctx = document.getElementById('horizontalBarChart').getContext('2d');
        var horizontalBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Buy',
                        data: buyData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        datalabels: {
                            align: 'end',
                            anchor: 'end',
                            offset: 4,
                            formatter: function(value, context) {
                                return Math.abs(value); // Menampilkan nilai absolut
                            }
                        }
                    },
                    {
                        label: 'Sell',
                        data: sellData.map(value => -value), // Negatifkan nilai sell untuk menampilkan di sebelah kanan
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        datalabels: {
                            align: 'start',
                            anchor: 'start',
                            offset: -4,
                            formatter: function(value, context) {
                                return Math.abs(value); // Menampilkan nilai absolut
                            }
                        }
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Gunakan sumbu Y sebagai sumbu indeks
                scales: {
                    x: {
                        display: false // Sembunyikan sumbu X
                    },
                    y: {
                        beginAtZero: true,
                        position: 'left', // Set posisi sumbu Y ke kiri
                        reverse: true, // Balik urutan data untuk memiliki label di sisi kiri
                        ticks: {
                            callback: function(value, index, values) {
                                return labels[values.length - index - 1]; // Perbarui label sumbu Y
                            }
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        display: true // Tampilkan label data
                    }
                }
            }
        });
    </script>
    