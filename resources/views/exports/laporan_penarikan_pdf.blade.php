<!-- resources/views/exports/laporan_penarikan_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penarikan Saldo</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h3 { text-align: center; margin-bottom: 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>

    <h3>Laporan Penarikan Saldo Seller</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Seller</th>
                <th>Jumlah Penarikan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bank</th>
                <th>Nomor Rekening</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($withdrawals as $index => $w)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $w->seller->brand_name ?? '-' }}</td>
                    <td class="text-right">Rp{{ number_format($w->amount, 0, ',', '.') }}</td>
                    <td>{{ $w->created_at->format('d M Y') }}</td>
                    <td>{{ ucfirst($w->status) }}</td>
                    <td>{{ $w->bank_name ?? 'Tidak tersedia' }}</td>
                    <td>{{ $w->bank_account ?? 'Tidak tersedia' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>Total Penarikan</strong></td>
                <td class="text-right"><strong>Rp{{ number_format($totalWithdrawn, 0, ',', '.') }}</strong></td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>
