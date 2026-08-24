<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="background-color: #f9fafb; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #374151; line-height: 1.6;">

    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #f3f4f6;">

        <h2 style="color: #111827; text-align: center; margin-bottom: 20px;">Reset Password Anda</h2>

        <p>Halo,</p>
        <p>Anda menerima email ini karena kami menerima permintaan untuk mereset password pada akun Anda. Silakan klik tombol di bawah ini untuk melanjutkan proses reset password.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #e92027; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block;">Reset Password</a>
        </div>

        <p>Link reset password ini akan kedaluwarsa dalam 10 menit.</p>
        <p>Jika Anda tidak pernah meminta reset password, Anda tidak perlu melakukan tindakan apapun.</p>

        <p>Salam hangat,<br><strong>Tim E-Arsip</strong></p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

        <p style="font-size: 12px; color: #6b7280; text-align: center;">
            Jika Anda kesulitan mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser web Anda:<br>
            <a href="{{ $url }}" style="color: #e92027; word-break: break-all;">{{ $url }}</a>
        </p>

    </div>

</body>
</html>
