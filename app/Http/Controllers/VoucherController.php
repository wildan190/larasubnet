<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class VoucherController extends Controller
{
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

    // Group hasil berdasarkan nama voucher
    $grouped = $allVouchers->groupBy('name');

    $voucherGroups = [];

    foreach ($grouped as $groupName => $vouchers) {
        $groupHash = md5($groupName);
        $pageName = "page_{$groupHash}";

        // Resolve current page untuk grup ini
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $perPage = 5;

        // Ambil slice data untuk current page
        $itemsForPage = $vouchers->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Buat paginator dengan pageName khusus (page_{groupHash})
        $paginator = new LengthAwarePaginator(
            $itemsForPage,
            $vouchers->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
                'query' => $request->except($pageName), // agar query filter lain tetap dipertahankan
            ]
        );

        $voucherGroups[$groupName] = [
            'hash' => $groupHash,
            'paginator' => $paginator,
        ];
    }

    return view('admin.vouchers.index', compact('voucherGroups'));
}
    // Menampilkan form untuk membuat voucher baru
    public function create()
    {
        return view('admin.vouchers.create');
    }

    // Menyimpan voucher baru ke database
public function storeMultiple(Request $request)
{
    $vouchersData = $request->input('vouchers');

    foreach ($vouchersData as $index => $voucherData) {
        $validated = Validator::make($voucherData, [
            'name' => 'required|string|max:255',
            'voucher_code' => 'required|string|unique:vouchers,voucher_code|max:255',
            'description' => 'nullable|string',
            'size' => 'required|string|max:5',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
        ])->validate();

        Voucher::create($validated);
    }

    return redirect()->route('admin.vouchers.index')->with('success', 'Vouchers berhasil dibuat!');
}

    // Menampilkan detail voucher berdasarkan ID
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }

    // Menampilkan form untuk mengedit voucher
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    // Menyimpan perubahan voucher yang sudah diedit
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voucher_code' => 'required|string|max:255|unique:vouchers,voucher_code,' . $id,
            'description' => 'nullable|string',
            'size' => 'required|string|max:255',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        // Menemukan voucher berdasarkan ID
        $voucher = Voucher::findOrFail($id);

        // Memperbarui data voucher
        $voucher->update($validated);

        // Redirect ke daftar voucher dengan pesan sukses
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    // Menghapus voucher berdasarkan ID
    public function destroy($id)
    {
        // Menemukan voucher berdasarkan ID
        $voucher = Voucher::findOrFail($id);

        // Menghapus voucher
        $voucher->delete();

        // Redirect ke daftar voucher dengan pesan sukses
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus!');
    }

    public function groupData(Request $request)
{
    $groupName = $request->query('group');

    // Ambil voucher berdasar grup (misal berdasarkan size atau nama grup sesuai logika kamu)
    // Contoh asumsi: $voucherGroups adalah koleksi keyed by groupName
    // Kamu sesuaikan ini dengan logic aslinya

    // Misal kamu punya method untuk get vouchers by group:
    $vouchers = Voucher::where('group_column', $groupName)->paginate(10);

    // Render partial blade dengan $vouchers
    return view('admin.vouchers._voucher_group_table', ['paginator' => $vouchers])->render();
}

}
