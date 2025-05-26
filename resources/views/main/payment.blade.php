@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <h3>Pembayaran: {{ $product->name }}</h3>
    <p>Harga: Rp {{ number_format($product->price) }}</p>

    <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>

    <!-- Midtrans Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js " data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            window.snap.pay("{{ $snapToken }}"), {
                onSuccess: function(result) {
                    window.location.href = "{{ route('main.downloads') }}";
                },
                onPending: function(result) {
                    alert("Pembayaran pending");
                },
                onError: function(result) {
                    alert("Pembayaran gagal");
                }
            };
        }
    </script>
</div>
@endsection
