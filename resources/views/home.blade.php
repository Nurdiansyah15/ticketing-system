@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="flex-grow: 1">
        <div class="p-4 rounded text-center"
            style="
            width: 100%;
            max-width: 500px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        ">
            <h2 class="mb-3 text-white">Dashboard</h2>

            @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @auth
                @if (auth()->user()->isAdmin())
                    <p class="text-white">Selamat datang, <strong>{{ auth()->user()->name }} (Admin)</strong>!</p>
                    <p class="text-white">Anda dapat mengelola daftar aduan, pengguna, dan mencetak laporan.</p>
                    <button class="btn border-0 text-white" style="background-color: #7B887F;"
                        onmouseover="this.style.backgroundColor='#96A691';" onmouseout="this.style.backgroundColor='#7B887F';"
                        onclick="window.location.href='{{ route('admin.tickets.index') }}'">
                        Kelola Aduan
                    </button>
                @else
                    <p class="text-white">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
                    <p class="text-white">Anda dapat membuat dan melihat daftar aduan.</p>
                    <button class="btn border-0 text-white" style="background-color: #7B887F;"
                        onmouseover="this.style.backgroundColor='#96A691';" onmouseout="this.style.backgroundColor='#7B887F';"
                        onclick="window.location.href='{{ route('tickets.index') }}'">
                        Lihat Aduan
                    </button>
                @endif
            @else
                <p>Silakan <a href="{{ route('login') }}">login</a> atau <a href="{{ route('register') }}">register</a>
                    untuk mengakses sistem.</p>
            @endauth
        </div>
    </div>
@endsection
