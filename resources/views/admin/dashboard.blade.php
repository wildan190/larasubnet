@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <h2>Dashboard Admin</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Pendapatan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Transaksi Settlement</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalTransactions }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Voucher Terjual</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalVouchers }}</h5>
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
            <canvas id="revenueChart" width="100%" height="40"></canvas>
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
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true,
                tension: 0.1,
            }]
        },
        options: {
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

    // Script untuk memastikan sidebar tetap di tempat saat scroll
    window.onscroll = function() { fixSidebar() };

    var sidebar = document.getElementById("sidebar");
    var sticky = sidebar.offsetTop;

    function fixSidebar() {
        if (window.pageYOffset >= sticky) {
            sidebar.classList.add("sticky");
        } else {
            sidebar.classList.remove("sticky");
        }
    }
</script>

@endsection
