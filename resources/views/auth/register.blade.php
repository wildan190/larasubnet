@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="card p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center">Register</h3>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
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
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <div class="text-center mt-3">
        <small>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></small>
    </div>
</div>
@endsection
