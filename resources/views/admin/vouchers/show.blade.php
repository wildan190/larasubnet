@extends('layouts.admin')

@section('title', 'Voucher Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark">Voucher Details</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left"></i> Back to Voucher List
        </a>
    </div>

    <!-- Voucher Details -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ $voucher->name }}</h5>
            <p class="card-text"><strong>Voucher Code:</strong> {{ $voucher->voucher_code }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $voucher->description }}</p>
            <p class="card-text"><strong>Size:</strong> {{ $voucher->size }}</p>
            <p class="card-text"><strong>Duration:</strong> {{ $voucher->duration }} Days</p>
            <p class="card-text"><strong>Price:</strong> Rp {{ number_format($voucher->price, 2, ',', '.') }}</p>
            <p class="card-text">
                <strong>Status:</strong> 
                <span class="badge {{ $voucher->isSold ? 'bg-danger' : 'bg-success' }}">
                    {{ $voucher->isSold ? 'Sold' : 'Available' }}
                </span>
            </p>
        </div>
    </div>
</div>
@endsection
