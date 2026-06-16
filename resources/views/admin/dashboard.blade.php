<x-layouts.admin>
    <div x-data="adminDashboard()" x-init="initCharts()">
        <h2 class="text-2xl font-bold text-coffee-800 mb-6">Dashboard</h2>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500 mb-1">Pendapatan Hari Ini</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500 mb-1">Transaksi Hari Ini</p>
                <p class="text-xl font-bold text-coffee-800">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500 mb-1">Rata-rata Order</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <p class="text-xs text-coffee-500 mb-1">Terlaris Hari Ini</p>
                <p class="text-xl font-bold text-coffee-800 truncate">{{ $bestSeller ? $bestSeller->menu_item_name . ' (' . $bestSeller->total_qty . ')' : '-' }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <h3 class="font-semibold text-coffee-700 mb-4">Pendapatan 7 Hari Terakhir</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="bg-white rounded-xl border border-coffee-100 p-4 shadow-sm">
                <h3 class="font-semibold text-coffee-700 mb-4">Jam Sibuk (Hari Ini)</h3>
                <canvas id="peakHoursChart" height="200"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function adminDashboard() {
            return {
                initCharts() {
                    // Revenue Chart
                    const revenueCtx = document.getElementById('revenueChart');
                    if (revenueCtx) {
                        new Chart(revenueCtx, {
                            type: 'line',
                            data: {
                                labels: @json($revenueChart->pluck('date')),
                                datasets: [{
                                    label: 'Pendapatan',
                                    data: @json($revenueChart->pluck('revenue')),
                                    borderColor: '#b8651a',
                                    backgroundColor: 'rgba(184,101,26,0.1)',
                                    fill: true,
                                    tension: 0.4,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => 'Rp' + new Intl.NumberFormat('id-ID').format(value)
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Peak Hours Chart
                    const peakCtx = document.getElementById('peakHoursChart');
                    if (peakCtx) {
                        new Chart(peakCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($peakHours->pluck('hour')),
                                datasets: [{
                                    label: 'Pesanan',
                                    data: @json($peakHours->pluck('orders')),
                                    backgroundColor: '#d4852a',
                                    borderRadius: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { stepSize: 1 }
                                    }
                                }
                            }
                        });
                    }
                }
            };
        }
    </script>
    @endpush
</x-layouts.admin>
