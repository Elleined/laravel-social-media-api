<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7fa;
            padding: 40px 0;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .email-header {
            background-color: #4f46e5;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .email-body h2 {
            margin-top: 0;
            font-size: 20px;
            color: #111827;
        }
        .email-body p {
            margin-bottom: 20px;
            color: #4b5563;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #4338ca;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
        .trouble-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <!-- Header -->
            <div class="email-header">
                <h1>Password Reset Request</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <h2>Hello {{ $user->name ?? 'there' }},</h2>
                
                <p>We received a request to reset your password for your account. If you didn't make this request, you can safely ignore this email.</p>
                
                <p>To choose a new password, click the button below:</p>
                
                <!-- CTA Button -->
                <div class="btn-container">
                    <a href="{{ $resetLink }}" target="_blank" class="btn">Reset Password</a>
                </div>
                
                
                <p>This password reset link will expire soon.</p>
                
                <p>Thanks,<br>
                The {{ config('app.name') }} Team</p>

                <!-- Fallback Link -->
                <div class="trouble-text">
                    <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
                    <a href="{{ $resetLink }}" style="color: #4f46e5;" target="_blank" >{{ $resetLink }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>