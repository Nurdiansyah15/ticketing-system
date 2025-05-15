@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="password-container p-4 rounded">

            <h2 class="mb-3 text-white text-center">Ubah Password</h2>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="mt-3">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label text-white">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control text-white transparent-input"
                        required>
                    @error('current_password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label text-white">Password Baru</label>
                    <input type="password" name="new_password" class="form-control text-white transparent-input" required>
                    @error('new_password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label text-white">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation"
                        class="form-control text-white transparent-input" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn primary-btn text-white">Simpan Perubahan</button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .password-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: auto;
        }

        .transparent-input {
            background-color: transparent !important;
            border: 1px solid white;
        }

        .primary-btn {
            background-color: #7B887F;
        }

        .primary-btn:hover {
            background-color: #96A691;
        }
    </style>
@endsection
