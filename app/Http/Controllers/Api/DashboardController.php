<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // API utama untuk data dashboard
    public function index()
    {
        // Total revenue dari transaksi yang berhasil
        $totalRevenue = Order::where('status', 'settlement')->sum('total_price');

        // Jumlah transaksi berhasil
        $totalTransactions = Order::where('status', 'settlement')->count();

        // Jumlah voucher unik yang digunakan dalam transaksi berhasil
        $totalVouchers = Order::where('status', 'settlement')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->distinct('order_items.voucher_id')
            ->count('order_items.voucher_id');

        // Pendapatan bulanan
        // --- BAGIAN INI YANG AKAN DIUPDATE UNTUK MEMPERBAIKI ERROR PostgreSQL ---
        $monthlyRevenue = Order::selectRaw("SUM(total_price) as total, TO_CHAR(order_date, 'YYYY-MM') as month")
            ->where('status', 'settlement')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $monthlyRevenue->pluck('month');
        $totals = $monthlyRevenue->pluck('total');

        // Voucher yang belum terjual
        $unsoldVouchers = Voucher::where('isSold', false)
            ->selectRaw('name, COUNT(*) as total')
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        // Response JSON
        return response()->json([
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'totalVouchers' => $totalVouchers,
            'monthlyRevenue' => [
                'months' => $months,
                'totals' => $totals,
            ],
            'unsoldVouchers' => $unsoldVouchers,
        ]);
    }
}