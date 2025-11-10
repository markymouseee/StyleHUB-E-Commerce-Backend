<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email</title>
</head>
<body>
<h2>Hello {{ $user->name }},</h2>

<p>Thank you for creating an account.</p>

<p>Please confirm your email address by clicking the link below:</p>

<p>
    <a href="{{ $url }}" target="_blank" style="color: white; background: #2563eb; padding: 10px 15px; text-decoration: none; border-radius: 6px;">
        Verify Email
    </a>
</p>

<p>If you did not request this account, please ignore this email.</p>

<br>
<p>Thank you!</p>
</body>
</html>
