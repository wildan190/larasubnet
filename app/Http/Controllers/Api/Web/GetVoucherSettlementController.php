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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $voucherId = $request->input('id');

        $voucher = Voucher::where('isSold', true)
            ->where('id', $voucherId)
            ->with(['orders:id,voucher_id,customer_name,customer_email'])
            ->first(['id', 'name', 'voucher_code', 'price']);

        if (! $voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found or not settled.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $voucher,
        ], 200);
    }
}
