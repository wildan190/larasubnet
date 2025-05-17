@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <h2 class="text-center text-md-start">Dashboard Admin</h2>

    <!-- 🔹 Cards Section -->
    <div class="row mt-4 g-3">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card text-white bg-success h-100">
                <div class="card-header">Total Pendapatan</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title text-center">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-header">Total Transaksi Settlement</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title text-center">{{ $totalTransactions }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-header">Total Voucher Terjual</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title text-center">{{ $totalVouchers }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Grafik Pendapatan Bulanan -->
    <div class="card mt-4">
        <div class="card-header">
            Grafik Pendapatan Bulanan
        </div>
        <div class="card-body">
            <canvas id="revenueChart" style="width: 100%; height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- 🔹 Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- 🔹 Script untuk menampilkan Chart.js -->
<script>
    // Inisialisasi Chart.js
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Parsing data bulanan ke format yang dibaca oleh Chart.js
    const labels = @json($months->map(function($month) { return date('F Y', strtotime($month)); }));
    const data = @json($totals);

    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                fill: true,
                tension: 0.1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Rp. ' + tooltipItem.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
