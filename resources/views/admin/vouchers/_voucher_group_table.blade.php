@if($paginator->count() == 0)
    <p class="text-muted p-3">Tidak ada voucher tersedia untuk grup ini.</p>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Voucher</th>
                    <th>Deskripsi</th>
                    <th>Ukuran</th>
                    <th>Durasi (Hari)</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paginator as $voucher)
                    <tr>
                        <td>{{ $voucher->voucher_code }}</td>
                        <td title="{{ $voucher->description }}">{{ $voucher->description ?: '-' }}</td>
                        <td>{{ $voucher->size }}</td>
                        <td>{{ $voucher->duration }}</td>
                        <td>Rp {{ number_format($voucher->price, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $voucher->isSold ? 'bg-danger' : 'bg-success' }}">
                                {{ $voucher->isSold ? 'Terjual' : 'Tersedia' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-info btn-sm me-1" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$voucher->isSold)
                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus voucher?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="voucher-group-pagination mt-3 d-flex justify-content-center">
        {!! $paginator->withQueryString()->links('pagination::bootstrap-5') !!}
    </div>
@endif
