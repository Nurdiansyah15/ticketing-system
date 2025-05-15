@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card p-4 rounded"
            style="
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        ">
            <h1 class="text-center" style="color: #fff; font-weight: bold;">Detail Aduan</h1>
            <div class="card-body">
                <h4 class="fw-bold mb-3 text-light">{{ $ticket->title }}</h4>
                <p class="text-light"><strong>Deskripsi:</strong> <span
                        class="text-white-50">{{ $ticket->description }}</span></p>
                <p class="text-light"><strong>Status:</strong>
                    <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </p>
                <p class="text-light"><i class="fas fa-calendar-alt"></i> <strong>Dibuat:</strong>
                    {{ $ticket->created_at->format('d M Y H:i') }}</p>
                <p class="text-light"><i class="fas fa-history"></i> <strong>Update:</strong>
                    {{ $ticket->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
