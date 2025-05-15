@extends('layouts.auth')

@section('content')
    <div class="d-flex align-items-center justify-content-center flex-grow-1">
        <div class="card p-4"
            style="
            width: 400px;
            background: linear-gradient(145deg, #7B887F, #96A691, #A3A9A5);
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
            <h4 class="fw-bold">Masuk</h4>
            <p class="text-light">Silakan masuk untuk melanjutkan</p>

            <!-- Flash Message -->
            @if (session('error'))
                <div class="alert alert-danger p-2">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="mb-0 text-start">
                        @foreach ($errors->all() as $error)
                            @if ($errors->count() == 1)
                                <p>{{ $errors->first() }}</p>
                            @else
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="text-start">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control border-0 p-2"
                        style="border-radius: 4px; background: #ffffff; color: #333;" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control border-0 p-2"
                        style="border-radius: 4px; background: #ffffff; color: #333;" required>
                    @error('password')
                        <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn w-100"
                    style="background-color: #646E67; color: white; border: none; border-radius: 4px; padding: 10px;
            transition: background-color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#646E67';" onmouseout="this.style.backgroundColor='#4F5852';">
                    Masuk
                </button>
            </form>

            <!-- Lupa Password & Register -->
            <div class="mt-3">
                <a href="#" class="d-block text-light">Lupa Password?</a>
                <a href="{{ route('register') }}" class="d-block text-light">Belum punya akun? Daftar</a>
            </div>
        </div>
    </div>
@endsection
