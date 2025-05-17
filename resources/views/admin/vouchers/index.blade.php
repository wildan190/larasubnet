@extends('layouts.admin')

@section('title', 'Daftar Voucher')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-dark">Daftar Voucher</h1>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary rounded-pill">
                <i class="fas fa-plus-circle"></i> Tambah Voucher
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $groupedVouchers = $vouchers->groupBy('name');
        @endphp

        <div class="accordion" id="voucherAccordion">
            @foreach ($groupedVouchers as $index => $voucherGroup)
                @php
                    $collapseId = 'collapse' . $index;
                    $headingId = 'heading' . $index;
                @endphp

                <div class="accordion-item mb-3 shadow-sm">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                            <div class="d-flex justify-content-between w-100 align-items-center">
                                <span>{{ $voucherGroup->first()->name }}</span>
                                <span class="badge bg-secondary">{{ $voucherGroup->count() }} item(s)</span>
                            </div>
                        </button>
                    </h2>
                    <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                        aria-labelledby="{{ $headingId }}" data-bs-parent="#voucherAccordion">
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No.</th>
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
                                        @foreach ($voucherGroup as $i => $voucher)
                                            @if (!$voucher->isSold)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $voucher->voucher_code }}</td>
                                                    <td>{{ Str::limit($voucher->description, 50) }}</td>
                                                    <td>{{ $voucher->size }}</td>
                                                    <td>{{ $voucher->duration }} Hari</td>
                                                    <td>Rp {{ number_format($voucher->price, 2, ',', '.') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Tersedia</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}"
                                                                class="btn btn-info btn-sm me-1">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                                                class="btn btn-warning btn-sm me-1">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <form
                                                                action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Yakin ingin menghapus voucher?')">
                                                                    <i class="fas fa-trash"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $vouchers->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
