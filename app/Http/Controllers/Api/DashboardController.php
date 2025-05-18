<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // API untuk menampilkan data dashboard
    public function index()
    {
        $totalRevenue = Order::where('status', 'settlement')->sum('total_price');
        $totalTransactions = Order::where('status', 'settlement')->count();
        $totalVouchers = Order::where('status', 'settlement')->distinct('voucher_id')->count();

        $monthlyRevenue = Order::selectRaw("SUM(total_price) as total, TO_CHAR(order_date, 'YYYY-MM') as month")
            ->where('status', 'settlement')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $months = $monthlyRevenue->pluck('month');
        $totals = $monthlyRevenue->pluck('total');

        $unsoldVouchers = Voucher::where('isSold', false)
            ->selectRaw('name, COUNT(*) as total')
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'totalVouchers' => $totalVouchers,
            'monthlyRevenue' => [
                'months' => $months,
                'totals' => $totals,
            ],
            'unsoldVouchers' => $unsoldVouchers,
        ], 200);
    }
}
