@extends('layouts.auth')

@section('content')
    <div class="d-flex align-items-center justify-content-center flex-grow-1">
        <div class="card p-4"
            style="
            width: 400px;
            background: linear-gradient(145deg, #7B887F, #96A691);
            border: 1px solid #646E67;
            border-radius: 6px;
            text-align: center;
            font-family: 'Segoe UI', sans-serif;
            color: #ffffff;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2);
        ">
            <!-- Logo -->
            <div class="mb-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80">
            </div>

            <!-- Judul -->
            <h4 class="fw-bold">Register</h4>
            <p class="text-light">Silakan buat akun baru</p>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="alert alert-success text-start">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-start">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger text-start">
                    @if ($errors->count() == 1)
                        <p>{{ $errors->first() }}</p>
                    @else
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="text-start">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-light">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-light">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-light">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-light">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn w-100"
                    style="background-color: #646E67; color: white; border: none; border-radius: 4px; padding: 10px;
                transition: background-color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#646E67';" onmouseout="this.style.backgroundColor='#4F5852';">
                    Daftar
                </button>

            </form>

            <!-- Login -->
            <div class="mt-3">
                <a href="{{ route('login') }}" class="d-block text-light">Sudah punya akun? Login</a>
            </div>
        </div>
    </div>
@endsection
