@if($paginator->count() == 0)
    <p class="text-muted p-3">Tidak ada voucher tersedia untuk grup ini.</p>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0 align-middle">
            <thead class="table-light text-nowrap">
                <tr>
                    <th>Kode Voucher</th>
                    <th style="max-width: 200px;">Deskripsi</th>
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
                        <td class="text-nowrap">{{ $voucher->voucher_code }}</td>
                        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $voucher->description }}">
                            {{ $voucher->description ?: '-' }}
                        </td>
                        <td class="text-nowrap">{{ $voucher->size }}</td>
                        <td class="text-nowrap">{{ $voucher->duration }}</td>
                        <td class="text-nowrap">Rp {{ number_format($voucher->price, 2, ',', '.') }}</td>
                        <td class="text-nowrap">
                            <span class="badge {{ $voucher->isSold ? 'bg-danger' : 'bg-success' }}">
                                {{ $voucher->isSold ? 'Terjual' : 'Tersedia' }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-wrap gap-1">
                                <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$voucher->isSold)
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus voucher?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
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
