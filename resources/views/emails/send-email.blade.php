<!DOCTYPE html>
<html>
<head>
    <title>Emailingizni Tasdiqlang</title>
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Workly - Email Tasdiqlash</h1>
        <p>Hurmatli {{ $user->name }},</p>
        <p>Workly platformasida ro'yxatdan o'tganingiz uchun tashakkur!</p>
        <p>Email manzilingizni quyidagi tugma orqali tasdiqlashingiz mumkin:</p>
        
        <a href="{{ $verificationUrl }}" class="button">Emailni Tasdiqlash</a>
        
        <p>Agar siz bu arizani yubormagan bo'lsangiz, hech qanday harakat qilishingiz shart emas.</p>
    </div>
</body>
</html>