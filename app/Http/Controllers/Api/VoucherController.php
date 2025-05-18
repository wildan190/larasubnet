<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * 📌 API: Menampilkan daftar voucher dengan filter dan paginasi per grup.
     */
    public function index(Request $request)
    {
        $voucherName = $request->input('voucher_name');
        $size = $request->input('size');
        $duration = $request->input('duration');
        $price = $request->input('price');

        // Mulai query, ambil voucher yang belum terjual
        $query = Voucher::query()->where('isSold', false);

        // Filter dinamis jika parameter ada
        if (!empty($voucherName)) {
            $query->where('name', 'like', '%' . $voucherName . '%');
        }
        if (!empty($size)) {
            $query->where('size', $size);
        }
        if (!empty($duration)) {
            $query->where('duration', $duration);
        }
        if (!empty($price)) {
            $query->where('price', '<=', $price);
        }

        // Ambil semua hasil dulu, ordered by name
        $allVouchers = $query->orderBy('name')->get();
        $grouped = $allVouchers->groupBy('name');

        $voucherGroups = [];

        foreach ($grouped as $groupName => $vouchers) {
            $groupHash = md5($groupName);
            $currentPage = LengthAwarePaginator::resolveCurrentPage($groupHash);
            $perPage = 5;
            $itemsForPage = $vouchers->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $paginator = new LengthAwarePaginator($itemsForPage, $vouchers->count(), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $groupHash,
            ]);

            $voucherGroups[$groupName] = [
                'hash' => $groupHash,
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'total' => $paginator->total()
                ]
            ];
        }

        return response()->json([
            'message' => 'Data voucher berhasil diambil',
            'voucherGroups' => $voucherGroups
        ], 200);
    }

    /**
     * 📌 API: Menyimpan voucher baru ke database (Multiple)
     */
    public function storeMultiple(Request $request)
    {
        $vouchersData = $request->input('vouchers');
        $createdVouchers = [];

        foreach ($vouchersData as $voucherData) {
            $validated = Validator::make($voucherData, [
                'name' => 'required|string|max:255',
                'voucher_code' => 'required|string|unique:vouchers,voucher_code|max:255',
                'description' => 'nullable|string',
                'size' => 'required|string|max:5',
                'duration' => 'required|integer',
                'price' => 'required|numeric',
            ])->validate();

            $voucher = Voucher::create($validated);
            $createdVouchers[] = $voucher;
        }

        return response()->json([
            'message' => 'Vouchers berhasil dibuat!',
            'vouchers' => $createdVouchers
        ], 201);
    }

    /**
     * 📌 API: Menampilkan detail voucher
     */
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return response()->json([
            'message' => 'Voucher ditemukan',
            'voucher' => $voucher
        ], 200);
    }

    /**
     * 📌 API: Memperbarui voucher berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voucher_code' => 'required|string|max:255|unique:vouchers,voucher_code,' . $id,
            'description' => 'nullable|string',
            'size' => 'required|string|max:255',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($validated);

        return response()->json([
            'message' => 'Voucher berhasil diperbarui!',
            'voucher' => $voucher
        ], 200);
    }

    /**
     * 📌 API: Menghapus voucher berdasarkan ID
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json([
            'message' => 'Voucher berhasil dihapus!'
        ], 200);
    }

    /**
     * 📌 API: Mengambil data voucher berdasarkan grup tertentu
     */
    public function groupData(Request $request)
    {
        $groupName = $request->query('group');
        $vouchers = Voucher::where('group_column', $groupName)->paginate(10);

        return response()->json([
            'message' => 'Data voucher berdasarkan grup berhasil diambil',
            'vouchers' => $vouchers
        ], 200);
    }
}
