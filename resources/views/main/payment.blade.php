@extends('layouts.main')

@section('content')
<div class="container text-center">
    <h3>Proses Pembayaran</h3>
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '/checkout/success'; // Atur sesuai route
            },
            onPending: function(result) {
                alert('Menunggu pembayaran Anda...');
                window.location.href = '/checkout/pending';
            },
            onError: function(result) {
                alert('Pembayaran gagal!');
                console.log(result);
            },
        });
    };
</script>
@endsection
