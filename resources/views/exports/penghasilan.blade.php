@php
    use Carbon\Carbon;

    $startDate = request('start_date');
    $endDate = request('end_date');
@endphp

<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
        <!-- Judul Laporan di dalam tabel -->
        <tr>
            <th colspan="6" style="border: 1px solid #ddd; padding: 16px; text-align: center; font-size: 20px;">
                Laporan Penghasilan&nbsp;&nbsp;
                <span style="font-weight: normal; font-size: 14px;">
                    @if($startDate && $endDate)
                        {{ Carbon::parse($startDate)->translatedFormat('d F Y') }}
                        s/d
                        {{ Carbon::parse($endDate)->translatedFormat('d F Y') }}
                    @else
                        Semua Tanggal
                    @endif
                </span>
            </th>
        </tr>
        <!-- Header Tabel -->
        <tr style="background-color: #4CAF50; color: white;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Nama Produk</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Pembeli</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Tanggal Pembayaran</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Status</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($penghasilan as $item)
            <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f9f9f9' : '#ffffff' }};">
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->product?->name ?? '-' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->user?->name ?? '-' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $item->created_at->format('d M Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    {{ $item->status === 'paid' ? 'Berhasil' : ucfirst($item->status) }}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="border: 1px solid #ddd; padding: 8px; text-align: center;">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
