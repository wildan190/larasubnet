<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.voucher', 'transaction']);

        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%'.$request->order_number.'%');
        }

        if ($request->filled('customer_email')) {
            $query->where('customer_email', 'like', '%'.$request->customer_email.'%');
        }

        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%'.$request->customer_name.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate($request->get('per_page', 10));

        $data = $orders->getCollection()->map(function ($order) {
            return [
                'order_number' => $order->order_number,
                'order_date' => $order->order_date,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'vouchers' => $order->orderItems->map(function ($item) {
                    $voucher = $item->voucher;

                    return $voucher ? [
                        'id' => $voucher->id,
                        'voucher_code' => $voucher->voucher_code,
                        'name' => $voucher->name,
                        'description' => $voucher->description,
                        'price' => $voucher->price,
                        'duration' => $voucher->duration,
                        'size' => $voucher->size,
                        'isSold' => $voucher->isSold,
                    ] : null;
                })->filter(),
                'transaction' => [
                    'transaction_number' => optional($order->transaction)->transaction_number,
                    'created_at' => optional($order->transaction)->created_at,
                ],
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ],
        ]);
    }
}
