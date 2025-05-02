@extends('layouts.seller')
@section('title', 'Produk Saya')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Kelola Produk Digital</h4>
        <div class="template-demo">
          <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-fw">Tambah Produk</a>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Nama</th>
                  <th>Penjualan</th>
                  <th>Harga</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                <tr>
                  <td class="py-1">
                    <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="image" style="width: 50px; height: 50px; object-fit: cover;" />
                  </td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->sales }}</td>
                  <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                  <td>
                    @if($product->status == 'pending')
                    <label class="badge badge-danger">Pending</label>
                    @elseif($product->status == 'active')
                    <label class="badge badge-success">Aktif</label>
                    @else
                    <label class="badge badge-secondary">Tidak Aktif</label>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info btn-sm">Detail</a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
    @vite('resources/js/seller/produk.js')
@endpush
