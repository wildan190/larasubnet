<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
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

        if (! $customerName && ! $customerEmail && ! $orderNumber) {
            return response()->json(
                [
                    'message' => 'Harap isi minimal satu dari: customer_name, customer_email, atau order_number.',
                ],
                422,
            );
        }

        $query->where('status', 'settlement');

        $orders = $query->with(['orderItems.voucher', 'transaction'])->get();

        return response()->json([
            'message' => 'Riwayat pesanan berhasil diambil.',
            'data' => $orders,
        ]);
    }
}
