@extends('layouts.app')

@section('content')
    <div class="container py-4">


        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="ticket-container p-4 rounded">
            <h2 class="mb-3 text-white text-center">Daftar Pengguna</h2>

            <div class="action-bar d-flex justify-content-between align-items-center mb-3 gap-2 overflow-auto">
                <!-- Add Ticket Button -->
                <a href="{{ route('admin.users.create') }}" class="btn text-white border-0 primary-btn">
                    Tambah Pengguna
                </a>

                <!-- Search and Pagination Form -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control text-white transparent-input"
                        value="{{ request('search') }}" placeholder="Cari judul atau status..." />

                    <select name="perPage" class="form-control text-white transparent-input" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $pageSize)
                            <option value="{{ $pageSize }}" {{ request('perPage') == $pageSize ? 'selected' : '' }}>
                                {{ $pageSize }} per halaman
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn text-white border-0 primary-btn">Cari</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            <!-- Tabel Daftar Pengguna -->
            <div class="table-responsive">
                <table class="table table-hover text-white">
                    <thead>
                        <tr>
                            @foreach (['name' => 'Nama', 'email' => 'Email', 'role' => 'Role'] as $column => $label)
                                <th>
                                    <a href="{{ route('admin.users.index', [
                                        'sort' => $column,
                                        'direction' => request('direction') === 'asc' ? 'desc' : 'asc',
                                        'search' => request('search'),
                                        'perPage' => request('perPage'),
                                    ]) }}"
                                        class="text-white text-decoration-none">
                                        {{ $label }}
                                        @if (request('sort') === $column)
                                            <i
                                                class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Pagination Controls -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="m-0 text-white">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</p>
                <nav>
                    <ul class="pagination m-0">
                        {{ $users->appends(request()->query())->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                    </ul>
                </nav>
            </div>


        </div>


    </div>

    <!-- Tambahkan CSS untuk scroll horizontal tanpa scrollbar -->
    <style>
        /* Container styling */
        .ticket-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .transparent-input {
            background-color: transparent !important;
            border: 1px solid #ccc;
        }

        .transparent-input option {
            background-color: white;
            color: black;
        }


        /* Button styling */
        .primary-btn {
            background-color: #7B887F;
        }

        .primary-btn:hover {
            background-color: #96A691;
        }

        /* Input styling */
        .transparent-input {
            min-width: 200px;
            background: transparent;
            border: 1px solid white;
        }

        /* Table styling */
        .table {
            background-color: transparent !important;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead tr,
        .table tbody tr,
        .table th,
        .table td {
            background: none !important;
            color: white !important;
            border: none;
            padding: 10px;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .table-responsive::-webkit-scrollbar {
            display: none;
        }

        .table thead {
            position: sticky;
            top: 0;
            background-color: rgba(0, 0, 0, 0.8) !important;
            z-index: 10;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {

            .table-responsive,
            .action-bar {
                display: block;
                white-space: nowrap;
                overflow-x: auto;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .table-responsive::-webkit-scrollbar,
            .action-bar::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
@endsection
