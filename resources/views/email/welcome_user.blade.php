<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
        }
        .wrapper {
            background-color: #f4f6f8;
            padding: 40px 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
        }
        .header {
            background-color: #4f46e5;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
        }
        .content p.first {
            margin-top: 0;
        }
        .btn-wrapper {
            margin: 30px auto;
        }
        .btn-cell {
            border-radius: 6px;
            background-color: #4f46e5;
        }
        .btn {
            font-size: 16px;
            font-family: inherit;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            padding: 12px 24px;
            display: inline-block;
            font-weight: 600;
        }
        .divider-container {
            padding: 0 30px;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 0;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #fafafa;
            font-size: 13px;
            color: #6b7280;
        }
        .footer p {
            margin: 0 0 10px 0;
        }
        .footer p.last {
            margin: 0;
        }
    </style>
</head>
<body>
    <table class="wrapper" role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table class="container" role="presentation" width="600" cellspacing="0" cellpadding="0">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <h1>
                                Welcome to {{ config('app.name') }}!
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content">
                            <p class="first">
                                Hi <strong>{{ $fullName }}</strong>,
                            </p>
                            <p>
                                We’re thrilled to have you on board! Your account has been created successfully, and you are ready to explore everything we have to offer.
                            </p>

                            <!-- CTA Button -->
                            <table class="btn-wrapper" role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="btn-cell" align="center">
                                        <a href="{{ config('app.frontend.url') }}/login" target="_blank" class="btn">
                                            Go to Login &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td class="divider-container">
                            <hr class="divider">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>
                                You are receiving this email because you recently signed up for an account at {{ config('app.name') }}.
                            </p>
                            <p class="last">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>