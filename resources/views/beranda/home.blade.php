<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <h1>Forex Sentiment</h1>
            </div>
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <canvas id="horizontalBarChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
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
    
    
    
</body>
</html>
