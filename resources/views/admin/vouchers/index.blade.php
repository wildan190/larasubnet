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

    <form method="GET" action="{{ route('admin.vouchers.index') }}" class="row g-3 mb-4" id="filterForm">
        <div class="col-md-3">
            <input type="text" name="voucher_name" class="form-control" placeholder="Cari Nama Voucher"
                value="{{ request('voucher_name') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="size" class="form-control" placeholder="Ukuran"
                value="{{ request('size') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="duration" class="form-control" placeholder="Durasi (Hari)"
                value="{{ request('duration') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="price" class="form-control" placeholder="Harga Maksimum"
                value="{{ request('price') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="accordion" id="voucherAccordion">
        @foreach($voucherGroups as $groupName => $groupData)
            @php
                $groupHash = $groupData['hash'];
                $paginator = $groupData['paginator'];
                $collapseId = 'collapse' . $groupHash;
                $headingId = 'heading' . $groupHash;
            @endphp
            <div class="accordion-item mb-3 shadow-sm">
                <h2 class="accordion-header" id="{{ $headingId }}">
                    <button class="accordion-button" type="button" aria-expanded="true" aria-controls="{{ $collapseId }}">
                        <div class="d-flex justify-content-between w-100 align-items-center">
                            <span>{{ $groupName }}</span>
                            <span class="badge bg-secondary">{{ $paginator->total() }} item(s)</span>
                        </div>
                    </button>
                </h2>
                <div id="{{ $collapseId }}" class="accordion-collapse collapse show" aria-labelledby="{{ $headingId }}">
                    <div class="accordion-body p-0" id="voucher-group-{{ $groupHash }}">
                        @include('admin.vouchers._voucher_group_table', ['paginator' => $paginator])
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="groupPagination" class="d-flex justify-content-center mt-3"></div>
</div>

{{-- JQuery CDN --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // Handle klik pagination di tiap accordion group
    $('#voucherAccordion').on('click', '.pagination a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        if (!url) return;

        // 🔄 Solusi Mixed Content: Hapus protokol agar relative ke halaman utama
        if (url.startsWith('http://') || url.startsWith('https://')) {
            url = url.replace(/^https?:/, '');
        }

        let params = new URLSearchParams(url.split('?')[1]);
        let pageParam = [...params.keys()].find(k => k.startsWith('page_'));
        if (!pageParam) return;

        let groupHash = pageParam.replace('page_', '');
        var $targetGroup = $('#voucher-group-' + groupHash);
        if ($targetGroup.length === 0) $targetGroup = $('#voucherAccordion');

        $.ajax({
            url: url,          // URL relative tanpa http atau https
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                var newHtml = $(data).find('#' + $targetGroup.attr('id')).html();
                $targetGroup.html(newHtml);
            },
            error: function() {
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        });
    });

    // Pagination untuk grup accordion jika grupnya banyak
    const maxGroupsPerPage = 10;
    const $groups = $('#voucherAccordion .accordion-item');
    const totalGroups = $groups.length;
    const totalPages = Math.ceil(totalGroups / maxGroupsPerPage);
    const $paginationContainer = $('#groupPagination');

    function renderGroupPagination(currentPage = 1) {
        $paginationContainer.empty();

        if (totalPages <= 1) {
            $groups.show();
            return;
        }

        $groups.hide();
        const startIndex = (currentPage - 1) * maxGroupsPerPage;
        const endIndex = startIndex + maxGroupsPerPage;
        $groups.slice(startIndex, endIndex).show();

        for (let i = 1; i <= totalPages; i++) {
            const $btn = $('<button>')
                .addClass('btn btn-sm me-1 ' + (i === currentPage ? 'btn-primary' : 'btn-outline-primary'))
                .text(i)
                .attr('data-page', i);

            $paginationContainer.append($btn);
        }
    }

    $paginationContainer.on('click', 'button', function () {
        const page = parseInt($(this).attr('data-page'));
        if (page) {
            renderGroupPagination(page);
            $('html, body').animate({ scrollTop: $('#voucherAccordion').offset().top }, 300);
        }
    });

    renderGroupPagination(1);
});
</script>

@endsection
