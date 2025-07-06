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
                <p class="text-light"><strong>Deskripsi:</strong>
                    <span class="text-white-50">{{ $ticket->description }}</span>
                </p>
                <p class="text-light"><strong>Status:</strong>
                    <span
                        class="badge
                        {{ $ticket->status == 'open'
                            ? 'bg-success'
                            : ($ticket->status == 'on_going'
                                ? 'bg-primary'
                                : ($ticket->status == 'waiting_for_resolved'
                                    ? 'bg-warning'
                                    : ($ticket->status == 'resolved'
                                        ? 'bg-secondary'
                                        : 'bg-dark'))) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </p>
                <p class="text-light"><i class="fas fa-calendar-alt"></i> <strong>Dibuat:</strong>
                    {{ $ticket->created_at->format('d M Y H:i') }}</p>
                <p class="text-light"><i class="fas fa-history"></i> <strong>Update:</strong>
                    {{ $ticket->updated_at->format('d M Y H:i') }}</p>
                <p class="text-light"><strong>Rating:</strong>
                    @if ($ticket->admin_rating > 0)
                        <span class="ms-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $ticket->admin_rating ? '-fill text-warning' : '' }}"></i>
                            @endfor
                        </span>
                    @else
                        <span class="text-muted">Belum dinilai</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Tombol Kembali dan Mulai --}}
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- Tombol Mulai (hanya untuk status open) --}}
            @if ($ticket->status === 'open')
                <form action="{{ route('tickets.start', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-warning">Mulai</button>
                </form>
            @endif
        </div>

        {{-- Percakapan dan Form Pesan --}}
        @if (!in_array($ticket->status, ['open', 'queue']))
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

            {{-- Form Kirim Pesan (hanya saat on_going) --}}
            @if ($ticket->status === 'on_going')
                <div class="mt-4">
                    <form action="{{ route('tickets.conversations.store', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label text-light">Pesan</label>
                            <textarea name="message" id="message" rows="3" class="form-control" required></textarea>
                        </div>
                        <button class="btn btn-primary">Ajukan Pertanyaan</button>
                    </form>
                </div>

                {{-- Ajukan Resolved (hanya admin) --}}
                @if (auth()->user()->role === 'admin')
                    <div class="mt-3">
                        <form action="{{ route('tickets.proposeResolved', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success">Ajukan Resolved</button>
                        </form>
                    </div>
                @endif
            @endif
        @endif
    </div>
@endsection
