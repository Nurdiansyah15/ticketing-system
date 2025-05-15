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
            <h1 class="text-center" style="color: #fff; font-weight: bold;">Edit Aduan</h1>

            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label text-light">Judul</label>
                    <input type="text" name="title" class="form-control"
                        style="background-color: transparent; border: 1px solid #fff; color: white;"
                        value="{{ old('title', $ticket->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label text-light">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="5"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" required>{{ old('description', $ticket->description) }}</textarea>
                </div>

                <button type="submit" class="btn w-100"
                    style="background-color: #646E67; color: white; border: none; border-radius: 4px; padding: 10px;
                transition: background-color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#4F5852';" onmouseout="this.style.backgroundColor='#646E67';">
                    Simpan Perubahan
                </button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-2 w-100">Batal</a>
            </form>
        </div>
    </div>
@endsection
