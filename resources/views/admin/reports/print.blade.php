<!DOCTYPE html>
<html>

    <head>
        <title>Laporan Pengaduan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table,
            th,
            td {
                border: 1px solid black;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .footer {
                margin-top: 20px;
                text-align: right;
                font-size: 12px;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h1>Laporan Pengaduan</h1>
            <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            <p><strong>Tanggal Export:</strong> {{ $exportDate }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Tanggal Diubah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                        <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $ticket->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Dicetak pada: {{ $exportDate }}</p>
        </div>
    </body>

</html>
