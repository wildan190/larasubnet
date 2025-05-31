<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class HistoryController extends Controller
{
    /**
     * Handle request to get order history based on customer_name,
     * customer_email, or order_number. One or more fields may be provided.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        $customerName = $request->input('customer_name');
        $customerEmail = $request->input('customer_email');
        $orderNumber = $request->input('order_number');

        if ($customerName) {
            $query->where('customer_name', 'LIKE', "%{$customerName}%");
        }

        if ($customerEmail) {
            $query->where('customer_email', 'LIKE', "%{$customerEmail}%");
        }

        if ($orderNumber) {
            $query->where('order_number', 'LIKE', "%{$orderNumber}%");
        }

        if (!$customerName && !$customerEmail && !$orderNumber) {
            return response()->json(
                [
                    'message' => 'Harap isi minimal satu dari: customer_name, customer_email, atau order_number.',
                ],
                422,
            );
        }

        // Tambahkan filter status settlement
        $query->where('status', 'settlement');

        $orders = $query->with(['voucher', 'transaction'])->get();

        return response()->json([
            'message' => 'Riwayat pesanan berhasil diambil.',
            'data' => $orders,
        ]);
    }
}
