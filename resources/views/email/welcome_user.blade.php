<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #333333;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4f46e5; padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">
                                Welcome to {{ config('app.name') }}!
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 16px; line-height: 1.6; margin-top: 0;">
                                Hi <strong>{{ $fullName }}</strong>,
                            </p>
                            <p style="font-size: 16px; line-height: 1.6;">
                                We’re thrilled to have you on board! Your account has been created successfully, and you are ready to explore everything we have to offer.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 30px auto;">
                                <tr>
                                    <td align="center" style="border-radius: 6px; background-color: #4f46e5;">
                                        <a href="{{ config('app.frontend.url') }}/login" target="_blank" style="font-size: 16px; font-family: inherit; color: #ffffff; text-decoration: none; border-radius: 6px; padding: 12px 24px; display: inline-block; font-weight: 600;">
                                            Go to Login &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 0;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; text-align: center; background-color: #fafafa; font-size: 13px; color: #6b7280;">
                            <p style="margin: 0 0 10px 0;">
                                You are receiving this email because you recently signed up for an account at {{ config('app.name') }}.
                            </p>
                            <p style="margin: 0;">
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