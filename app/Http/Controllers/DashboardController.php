<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Menampilkan Dashboard
    public function index()
{
    // ✅ Ambil Total Pendapatan dari Order yang settlement
    $totalRevenue = Order::where('status', 'settlement')->sum('total_price');

    // ✅ Ambil Total Transaksi yang settlement
    $totalTransactions = Order::where('status', 'settlement')->count();

    // ✅ Ambil Total Voucher yang terjual
    $totalVouchers = Order::where('status', 'settlement')->distinct('voucher_id')->count();

    // ✅ Ambil Pendapatan Bulanan
    $monthlyRevenue = Order::selectRaw("SUM(total_price) as total, TO_CHAR(order_date, 'YYYY-MM') as month")
        ->where('status', 'settlement')
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

    // 🔹 Pisahkan data bulanan
    $months = $monthlyRevenue->pluck('month');
    $totals = $monthlyRevenue->pluck('total');

    // ✅ Kembalikan ke view
    return view('admin.dashboard', compact(
        'totalRevenue',
        'totalTransactions',
        'totalVouchers',
        'months',
        'totals'
    ));
}

}
