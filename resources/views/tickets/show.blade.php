@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card p-4 rounded"
            style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
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
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('tickets.edit', $ticket) }}"
                class="btn btn-warning {{ $ticket->status !== 'open' ? 'disabled' : '' }}">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>

        {{-- Percakapan --}}
        @if ($ticket->status)
            <div class="mt-5">
                <h4 class="text-light">Percakapan</h4>
                <div class="list-group bg-transparent" style="max-height: 300px; overflow-y: auto;">
                    @forelse ($ticket->conversations as $conversation)
                        <div class="list-group-item bg-dark text-light rounded mb-2">
                            <strong>
                                {{ $conversation->sender_type === 'admin' ? 'Admin' : 'User (' . $conversation->user->name . ')' }}:
                            </strong>
                            <p class="mb-0">{{ $conversation->message }}</p>
                            <small class="text-white-50">{{ $conversation->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-white-50">Belum ada percakapan.</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- Form Balas (selama belum resolved dan belum waiting_for_resolved) --}}
        @if (!in_array($ticket->status, ['resolved', 'waiting_for_resolved']))
            <div class="mt-4">
                <form action="{{ route('tickets.conversations.store', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label text-light">Balasan Anda</label>
                        <textarea name="message" id="message" rows="3" class="form-control" required></textarea>
                    </div>
                    <button class="btn btn-primary">Kirim Balasan</button>
                </form>
            </div>
        @endif

        {{-- Form Terima/Tolak Resolusi --}}
        @if ($ticket->status === 'waiting_for_resolved')
            <div class="mt-4">
                {{-- Tombol Terima --}}
                <form action="{{ route('tickets.markResolved', $ticket->id) }}" method="POST" class="d-inline-block me-2">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success">Terima Resolusi</button>
                </form>

                {{-- Tolak dengan pesan --}}
                <form action="{{ route('tickets.notResolved', $ticket->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="mb-2">
                        <label for="reason" class="form-label text-light">Alasan Penolakan</label>
                        <textarea name="message" id="reason" rows="3" class="form-control" required></textarea>
                    </div>
                    <button class="btn btn-danger">Tolak Resolusi</button>
                </form>
            </div>
        @endif

    </div>
@endsection
