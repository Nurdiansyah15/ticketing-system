@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="ticket-container p-4 rounded">
            <h2 class="mb-3 text-white text-center">Laporan Pengaduan</h2>

            <!-- Form untuk Memilih Range Tanggal -->
            <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="text-white mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control text-white transparent-input" value="{{ request('start_date') }}"
                            required />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="text-white mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date"
                            class="form-control text-white transparent-input" value="{{ request('end_date') }}" required />
                    </div>
                    <div class="col-md-4 d-flex align-items-end mb-3">
                        <button type="submit" class="btn text-white border-0 primary-btn me-2">Preview</button>
                        <a href="{{ route('admin.reports.print', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                            class="btn btn-secondary {{ request('start_date') && request('end_date') ? '' : 'disabled' }}">
                            Cetak Laporan
                        </a>
                    </div>
                </div>
            </form>

            <!-- Tabel Daftar Pengaduan -->
            @if (request('start_date') && request('end_date'))
                <div class="table-responsive">
                    <table class="table table-hover text-white">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->title }}</td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                    <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data untuk rentang tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
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

        .transparent-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
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
            .row {
                flex-direction: column;
            }

            .table-responsive {
                display: block;
                white-space: nowrap;
                overflow-x: auto;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .table-responsive::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
@endsection
