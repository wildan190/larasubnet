<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    // Menampilkan daftar semua voucher
    public function index()
    {
        // Mengambil data voucher dengan pagination
        $vouchers = Voucher::paginate(10); // 10 data per halaman

        // Mengembalikan view dengan data vouchers dan pagination
        return view('admin.vouchers.index', compact('vouchers'));
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
            'size' => 'required|string|max:255',
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
}
