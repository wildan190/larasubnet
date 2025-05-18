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
                    <h5 class="card-title text-center">
                        Rp. {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-header">Total Transaksi Settlement</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title text-center">
                        {{ $totalTransactions ?? 0 }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-header">Total Voucher Terjual</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title text-center">
                        {{ $totalVouchers ?? 0 }}
                    </h5>
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
            @if(count($months) > 0)
                <canvas id="revenueChart" style="width: 100%; height: 300px;"></canvas>
            @else
                <div class="alert alert-warning text-center">
                    Grafik tidak tersedia karena tidak ada data transaksi.
                </div>
            @endif
        </div>
    </div>

    <!-- 🔹 Tabel Voucher Belum Terjual -->
    <div class="card mt-4">
        <div class="card-header">
            Daftar Voucher Belum Terjual
        </div>
        <div class="card-body">
            @if(count($unsoldVouchers) > 0)
                <table id="voucherTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Voucher</th>
                            <th>Jumlah Tersisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unsoldVouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->name }}</td>
                                <td>
                                    {{ $voucher->total }}
                                    @if($voucher->total <= 5) <!-- Batas stok kritis -->
                                        <span class="badge bg-danger ms-2">Stok Menipis!</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    Tidak ada voucher yang belum terjual.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- 🔹 Load jQuery dan DataTables dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- 🔹 Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- 🔹 Inisialisasi DataTables -->
<script>
    $(document).ready(function () {
        $('#voucherTable').DataTable({
            "pageLength": 5,   // Jumlah baris per halaman
            "lengthChange": true, // Pilihan jumlah baris
            "searching": true, // Fitur pencarian
            "ordering": true, // Sorting kolom
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari:",
                "paginate": {
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });

    // 🔹 Inisialisasi Chart.js
    const ctx = document.getElementById('revenueChart')?.getContext('2d');
    const labels = @json($months->map(function($month) { return date('F Y', strtotime($month)); }) ?? []);
    const data = @json($totals ?? []);

    if (ctx) {
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
    }
</script>
@endsection
