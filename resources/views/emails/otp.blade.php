<!DOCTYPE html>
<html>
<head>
    <title>Reset Password OTP</title>
</head>
<body>
    <h1>Reset Password OTP</h1>
    <p>Halo,</p>
    <p>Kami telah menerima permintaan untuk mereset kata sandi Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>
    <h2>{{ $otp }}</h2>
    <p>Kode ini berlaku selama 10 menit. Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>
    <p>Terima kasih,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
