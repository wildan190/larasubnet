@extends('layouts.admin')

@section('title', 'Daftar Voucher')

@section('content')
<div class="container-fluid">
    <!-- Header dengan judul dan tombol tambah voucher -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark">Daftar Voucher</h1>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus-circle"></i> Tambah Voucher
        </a>
    </div>

    <!-- Menampilkan pesan sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Voucher -->
    <div class="table-responsive shadow-sm rounded-3 border">
        <table class="table table-hover table-striped align-middle">
            <thead class="bg-light">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Kode Voucher</th>
                    <th>Deskripsi</th>
                    <th>Ukuran</th>
                    <th>Durasi</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $voucher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $voucher->name }}</td>
                    <td>{{ $voucher->voucher_code }}</td>
                    <td>{{ Str::limit($voucher->description, 50) }}</td>
                    <td>{{ $voucher->size }}</td>
                    <td>{{ $voucher->duration }} Hari</td>
                    <td>Rp {{ number_format($voucher->price, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $voucher->isSold ? 'bg-danger' : 'bg-success' }}">
                            {{ $voucher->isSold ? 'Terjual' : 'Tersedia' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-info btn-sm me-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            @if(!$voucher->isSold)
                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus voucher?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $vouchers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
