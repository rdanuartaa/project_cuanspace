<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Pembeli</th>
            <th>Tanggal Pembayaran</th>
            <th>Status</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($penghasilan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product?->name ?? '-' }}</td>
                <td>{{ $item->user?->name ?? '-' }}</td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
                <td>{{ $item->status === 'paid' ? 'Berhasil' : ucfirst($item->status) }}</td>
                <td>Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
