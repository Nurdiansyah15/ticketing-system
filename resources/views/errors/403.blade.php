@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 70vh;">
        <div class="card shadow-sm p-4 text-center" style="max-width: 500px; width: 100%;">
            <h1 class="display-4 text-warning mb-3">403</h1>
            <p class="lead text-muted mb-3">
                {{-- Tampilkan pesan custom jika ada --}}
                {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
            </p>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
