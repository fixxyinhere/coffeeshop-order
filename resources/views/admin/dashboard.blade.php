<x-layouts.admin>
    <div x-data="adminDashboard()" x-init="initCharts()" class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h2 class="font-heading text-3xl text-coffee-800">Dashboard</h2>
            <p class="text-sm text-coffee-500 mt-1">Ringkasan bisnis {{ date('d F Y') }}</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <x-stat-card label="Pendapatan Hari Ini" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" color="green" />
            <x-stat-card label="Transaksi Hari Ini" value="{{ $totalTransactions }}" color="coffee" />
            <x-stat-card label="Rata-rata Order" value="Rp {{ number_format($averageOrder, 0, ',', '.') }}" color="blue" />
            <x-stat-card label="Terlaris" value="{{ $bestSeller ? $bestSeller->menu_item_name . ' ('.$bestSeller->total_qty.')' : '-' }}" color="purple" />
        </div>

        <!-- Charts -->
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="card p-5">
                <h3 class="font-heading text-lg text-coffee-800 mb-4">Pendapatan 7 Hari Terakhir</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="card p-5">
                <h3 class="font-heading text-lg text-coffee-800 mb-4">Jam Sibuk (Hari Ini)</h3>
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
