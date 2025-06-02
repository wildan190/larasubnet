<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a paginated list of orders with optional filters.
     */
    public function index(Request $request)
    {
        $query = Order::with(['voucher', 'transaction']);

        // Apply filters if present
        if ($request->has('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        if ($request->has('customer_email')) {
            $query->where('customer_email', 'like', '%' . $request->customer_email . '%');
        }

        if ($request->has('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Paginate results (default 10 per page)
        $orders = $query->orderBy('order_date', 'desc')->paginate($request->get('per_page', 10));

        return response()->json($orders);
    }
}
