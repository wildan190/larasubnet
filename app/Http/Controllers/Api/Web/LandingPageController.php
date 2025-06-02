<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class LandingPageController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = true; // Ubah ke true jika di production
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Fetch all available vouchers
    public function index()
    {
        $vouchers = Voucher::where('isSold', false)->get(['id', 'name', 'description', 'price', 'size', 'duration']);

        if ($vouchers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No vouchers available',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $voucher = Voucher::find($request->voucher_id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $order = Order::create([
            'voucher_id' => $voucher->id,
            'order_number' => strtoupper(uniqid('ORD-', true)),
            'order_date' => Carbon::now(),
            'total_price' => $voucher->price,
            'status' => 'pending',
            'customer_name' => $request->name,
            'customer_email' => $request->email,
        ]);

        $transaction = Transaction::create([
            'order_id' => $order->id,
            'transaction_number' => strtoupper(uniqid('TXN-', true)),
        ]);

        $midtransTransaction = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $voucher->price,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
            ],
        ];

        if ($voucher->price >= 1 && $voucher->price <= 29000) {
            $midtransTransaction['enabled_payments'] = ['gopay', 'shopeepay', 'dana'];
        }

        $snapToken = Snap::getSnapToken($midtransTransaction);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order' => $order,
        ]);
    }

    public function handleNotification(Request $request)
    {
        $notif = $request->all();

        $orderId = $notif['order_id'];
        $statusCode = $notif['status_code'];
        $grossAmount = $notif['gross_amount'];
        $serverKey = config('midtrans.server_key');

        $inputSignature = $notif['signature_key'];
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($inputSignature !== $expectedSignature) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $notif['transaction_status'];
        $transactionId = $notif['transaction_id'];

        if ($transactionStatus == 'settlement') {
            $voucher = $order->voucher;
            $voucher->isSold = true;
            $voucher->save();

            $order->status = 'settlement';
            $order->save();

            TransactionLog::create([
                'order_id' => $order->id,
                'transaction_status' => $transactionStatus,
                'action' => 'Payment successful',
                'transaction_id' => $transactionId,
                'notification' => json_encode($notif),
            ]);

            $customer_name = $order->customer_name;
            $customer_email = $order->customer_email;

            $voucher = $order->voucher;

            $pdf = PDF::loadView('pdf.voucher', [
                'order' => $order,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'voucher_code' => $voucher->voucher_code,
                'voucher_name' => $voucher->name,
                'duration' => $voucher->duration,
                'price' => $voucher->price,
            ]);

            if (!Storage::disk('public')->exists('vouchers')) {
                Storage::disk('public')->makeDirectory('vouchers');
            }

            $fileName = 'Voucher_' . $order->order_number . '.pdf';
            Storage::disk('public')->put('vouchers/' . $fileName, $pdf->output());

            $downloadUrl = url('/api/download-pdf/' . $order->order_number);

            return response()->json([
                'message' => 'Notification received',
                'download_link' => $downloadUrl,
            ]);
        } elseif ($transactionStatus == 'pending') {
            $order->status = 'pending';
            $order->save();
        } elseif ($transactionStatus == 'cancel') {
            $order->status = 'cancelled';
            $order->save();
        }

        return response()->json(['message' => 'Notification received']);
    }

    public function downloadPDF($orderNumber)
    {
        $filePath = 'vouchers/Voucher_' . $orderNumber . '.pdf';

        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function getVoucher($id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher || $voucher->isSold) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucherData = $voucher->toArray();
        unset($voucherData['voucher_code']);

        return response()->json([
            'success' => true,
            'data' => $voucherData,
        ]);
    }
}
