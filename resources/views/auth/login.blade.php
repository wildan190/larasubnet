@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="card p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center">Login</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    {{-- <div class="text-center mt-3">
        <small>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></small>
    </div> --}}
</div>
@endsection
