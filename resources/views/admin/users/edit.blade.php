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
            <h1 class="text-center" style="color: #fff; font-weight: bold;">Edit Pengguna</h1>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label text-light">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" required />
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-light">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" required />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-light">Password (Kosongkan jika tidak ingin
                        mengubah)</label>
                    <input type="password" name="password" class="form-control"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" />
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-light">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" />
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label text-light">Role</label>
                    <select name="role" class="form-control"
                        style="background-color: transparent; border: 1px solid #fff; color: white;" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}
                            style="background-color: #333; color: white;">Admin</option>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}
                            style="background-color: #333; color: white;">User</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn flex-grow-1"
                        style="background-color: #646E67; color: white; border: none; border-radius: 4px; padding: 10px;
                        transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#4F5852';"
                        onmouseout="this.style.backgroundColor='#646E67';">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex-grow-1">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
