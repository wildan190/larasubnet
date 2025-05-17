<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class GetVoucherSettlementController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        // Ambil parameter ID dari request
        $voucherId = $request->input('id');

        // Cari voucher yang sudah settlement berdasarkan ID
        $voucher = Voucher::where('isSold', true)
            ->where('id', $voucherId)
            ->with(['orders:id,voucher_id,customer_name,customer_email'])
            ->first(['id', 'name', 'voucher_code', 'price']);

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found or not settled.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $voucher
        ], 200);
    }
}