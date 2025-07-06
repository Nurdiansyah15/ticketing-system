@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 70vh;">
        <div class="card shadow-sm p-4 text-center" style="max-width: 500px; width: 100%;">
            <img src="/assets/images/404.svg" alt="404 Not Found" class="mb-4" style="max-width: 250px; margin: 0 auto;">
            <h1 class="display-4 text-danger mb-2">404</h1>
            <p class="lead text-muted mb-3">Halaman yang Anda cari tidak ditemukan.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
        </div>
    </div>
@endsection
