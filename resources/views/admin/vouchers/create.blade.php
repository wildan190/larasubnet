@extends('layouts.admin')

@section('title', 'Create Voucher')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark">Create New Voucher</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left"></i> Back to Voucher List
        </a>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Voucher Create Form -->
    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Voucher Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="voucher_code" class="form-label">Voucher Code</label>
                    <input type="text" class="form-control" id="voucher_code" name="voucher_code" value="{{ old('voucher_code') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Size (e.g. 10GB)</label>
                    <input type="text" class="form-control" id="size" name="size" value="{{ old('size') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (Days)</label>
                    <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration') }}" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price (in IDR)</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="isSold" name="isSold">
                    <label class="form-check-label" for="isSold">Sold</label>
                </div>
                <button type="submit" class="btn btn-primary">Create Voucher</button>
            </div>
        </div>
    </form>
</div>
@endsection
