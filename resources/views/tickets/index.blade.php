@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel Tiket Aktif -->
        <div class="ticket-container p-4 rounded mb-5">
            <h2 class="mb-3 text-white text-center">Daftar Aduan</h2>

            <div class="action-bar d-flex justify-content-between align-items-center mb-3 gap-2 overflow-auto">
                <a href="{{ route('tickets.create') }}" class="btn text-white border-0 primary-btn">Tambah Aduan</a>

                <form action="{{ route('tickets.index') }}" method="GET" class="d-flex gap-2">
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
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-white">
                    <thead>
                        <tr>
                            @php
                                $columns = [
                                    'title' => 'Judul',
                                    'admin_id' => 'Admin',
                                    'status' => 'Status',
                                    'updated_at' => 'Terakhir Diubah',
                                ];
                            @endphp

                            @foreach ($columns as $column => $label)
                                <th>
                                    <a href="{{ route('tickets.index', [
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
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ $ticket->admin->name }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                <td>{{ $ticket->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('tickets.edit', $ticket) }}"
                                        class="btn btn-warning btn-sm {{ !in_array($ticket->status, ['open', 'queue']) ? 'disabled' : '' }}"
                                        @if (!in_array($ticket->status, ['open', 'queue'])) aria-disabled="true" @endif>
                                        Edit
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada aduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="m-0 text-white">Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}</p>
                <nav>
                    <ul class="pagination m-0">
                        {{ $tickets->appends(request()->query())->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Tabel Tiket Resolved -->
        <div class="ticket-container p-4 rounded">
            <h2 class="mb-3 text-white text-center">Daftar Aduan Selesai</h2>

            <div class="table-responsive">
                <table class="table table-hover text-white">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Terakhir Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resolvedTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                <td>{{ $ticket->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada aduan selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
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
