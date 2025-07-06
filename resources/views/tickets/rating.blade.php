@extends('layouts.app')

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow-sm p-4" style="max-width: 600px; width: 100%;">

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Avatar dan Info --}}
            <div class="text-center mb-3">
                <img src="/assets/images/user.png" alt="Avatar" width="60" height="60" class="rounded-circle mb-2" />
                <div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                    <small class="text-muted">{{ ucfirst($user->role) }}</small>
                </div>
            </div>

            {{-- Judul Tiket --}}
            <p class="text-center fw-bold">{{ $ticket->title }}</p>

            {{-- Form Rating --}}
            <form action="{{ route('tickets.rating.submit', $ticket->id) }}" method="POST" class="text-center">
                @csrf
                <div class="mb-3">
                    <div class="d-flex flex-row-reverse justify-content-center" style="gap: 0.25rem;">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                class="d-none" required>
                            <label for="star{{ $i }}" style="font-size: 2rem; color: #ddd; cursor: pointer;"
                                onmouseover="highlightStars({{ $i }})" onmouseout="resetStars()"
                                onclick="selectStar({{ $i }})" id="label-star{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Rating</button>
            </form>
        </div>
    </div>

    {{-- Script Rating --}}
    <script>
        let selectedRating = 0;

        function highlightStars(star) {
            for (let i = 1; i <= 5; i++) {
                const label = document.getElementById('label-star' + i);
                label.style.color = (i <= star) ? '#ffc107' : '#ddd';
            }
        }

        function resetStars() {
            highlightStars(selectedRating);
        }

        function selectStar(star) {
            selectedRating = star;
            highlightStars(star);
        }

        document.addEventListener('DOMContentLoaded', function() {
            resetStars();
        });
    </script>
@endsection
