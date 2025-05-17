@extends('layouts.admin')

@section('title', 'Create Multiple Vouchers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark">Create Multiple Vouchers</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left"></i> Back to Voucher List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="voucherForm" action="{{ route('admin.vouchers.storeMultiple') }}" method="POST">
        @csrf

        <!-- Opsi gunakan nama yang sama untuk semua voucher -->
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="useSameName" name="use_same_name" checked>
            <label class="form-check-label" for="useSameName">Gunakan nama voucher yang sama untuk semua</label>
        </div>

        <!-- Input nama voucher utama jika pakai nama sama -->
        <div class="mb-3" id="mainNameInputContainer">
            <label>Nama Produk</label>
            <input type="text" id="mainVoucherName" class="form-control" name="main_voucher_name" required>
        </div>

        <div id="voucher-forms-container">
            <div class="voucher-form border p-3 mb-3">
                <h5>Voucher 1</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label>Nama Voucher</label>
                        <input type="text" name="vouchers[0][name]" class="form-control voucher-name-input" required>
                    </div>
                    <div class="col-md-6">
                        <label>Kode Voucher</label>
                        <input type="text" name="vouchers[0][voucher_code]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Deskripsi</label>
                        <textarea name="vouchers[0][description]" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Ukuran</label>
                        <input type="text" name="vouchers[0][size]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Durasi (hari)</label>
                        <input type="number" name="vouchers[0][duration]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Harga (IDR)</label>
                        <input type="number" name="vouchers[0][price]" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="add-voucher-btn" class="btn btn-success mb-3">+ Tambah Voucher</button>
        <br>
        <button type="submit" class="btn btn-primary">Buat Voucher</button>
    </form>
</div>

<script>
    let voucherIndex = 1; // sudah ada 1 form default

    const useSameNameCheckbox = document.getElementById('useSameName');
    const mainNameInputContainer = document.getElementById('mainNameInputContainer');
    const mainVoucherNameInput = document.getElementById('mainVoucherName');
    const voucherFormsContainer = document.getElementById('voucher-forms-container');

    // Fungsi untuk update input name tiap voucher sesuai kondisi useSameName
    function updateVoucherNameInputs() {
        const useSameName = useSameNameCheckbox.checked;

        // Tampilkan atau sembunyikan input nama utama
        mainNameInputContainer.style.display = useSameName ? 'block' : 'none';

        // Update tiap input voucher name
        document.querySelectorAll('.voucher-name-input').forEach(input => {
            if(useSameName) {
                input.disabled = true;
                input.required = false;
                input.value = mainVoucherNameInput.value; // isi sama dengan utama
            } else {
                input.disabled = false;
                input.required = true;
                if(input.value === mainVoucherNameInput.value) input.value = ''; // kosongkan kalau sebelumnya copy
            }
        });
    }

    // Event listener checkbox
    useSameNameCheckbox.addEventListener('change', () => {
        updateVoucherNameInputs();
    });

    // Update nama voucher tiap kali input utama berubah kalau pakai nama sama
    mainVoucherNameInput.addEventListener('input', () => {
        if(useSameNameCheckbox.checked) {
            updateVoucherNameInputs();
        }
    });

    // Add voucher baru
    document.getElementById('add-voucher-btn').addEventListener('click', function() {
        const voucherForm = document.createElement('div');
        voucherForm.classList.add('voucher-form', 'border', 'p-3', 'mb-3');
        voucherForm.innerHTML = `
            <h5>Voucher ${voucherIndex + 1} <button type="button" class="btn btn-danger btn-sm float-end remove-voucher-btn">Remove</button></h5>
            <div class="row">
                <div class="col-md-6">
                    <label>Nama Voucher</label>
                    <input type="text" name="vouchers[${voucherIndex}][name]" class="form-control voucher-name-input" required>
                </div>
                <div class="col-md-6">
                    <label>Kode Voucher</label>
                    <input type="text" name="vouchers[${voucherIndex}][voucher_code]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label>Deskripsi</label>
                    <textarea name="vouchers[${voucherIndex}][description]" class="form-control"></textarea>
                </div>
                <div class="col-md-6 mt-3">
                    <label>Ukuran</label>
                    <input type="text" name="vouchers[${voucherIndex}][size]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label>Durasi (hari)</label>
                    <input type="number" name="vouchers[${voucherIndex}][duration]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label>Harga (IDR)</label>
                    <input type="number" name="vouchers[${voucherIndex}][price]" class="form-control" required>
                </div>
            </div>
        `;

        voucherFormsContainer.appendChild(voucherForm);

        // Pasang event listener tombol remove
        voucherForm.querySelector('.remove-voucher-btn').addEventListener('click', () => {
            voucherForm.remove();
        });

        voucherIndex++;

        // Update input nama jika pakai nama sama
        updateVoucherNameInputs();
    });

    // Jalankan update awal
    updateVoucherNameInputs();

    // Sebelum submit, kalau pakai nama sama, set semua input voucher name agar punya value sama dengan input utama
    document.getElementById('voucherForm').addEventListener('submit', function(e) {
        if(useSameNameCheckbox.checked) {
            const nameVal = mainVoucherNameInput.value.trim();
            if(!nameVal) {
                e.preventDefault();
                alert('Nama voucher utama harus diisi!');
                mainVoucherNameInput.focus();
                return false;
            }
            document.querySelectorAll('.voucher-name-input').forEach(input => {
                input.disabled = false; // enable dulu supaya terkirim
                input.value = nameVal;
            });
        }
    });
</script>
@endsection
