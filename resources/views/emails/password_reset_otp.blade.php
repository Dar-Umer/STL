<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f4f7f5; padding: 30px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 18px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #14532d 0%, #0f4426 100%); padding: 32px 24px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 0.3px; color: #ffffff;">
                                {{ $appName ?? 'Soil Testing Laboratory' }}
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 13px; color: #bbf7d0; font-weight: 500;">
                                Department of Horticulture &bull; Password Reset Request
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <p style="margin: 0 0 16px; font-size: 15px; color: #1e293b; font-weight: 600;">
                                Hello {{ $userName ?? 'Official' }},
                            </p>
                            <p style="margin: 0 0 24px; font-size: 14px; line-height: 1.6; color: #475569;">
                                We received a request to reset your password for your <strong>Soil Testing Laboratory</strong> account. Use the verification code (OTP) below to proceed with resetting your password:
                            </p>

                            <!-- OTP Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 28px 0;">
                                <tr>
                                    <td align="center" style="background-color: #f0fdf4; border: 2px dashed #86efac; border-radius: 14px; padding: 22px 16px;">
                                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #15803d; display: block; margin-bottom: 8px;">
                                            Your One-Time Passcode (OTP)
                                        </span>
                                        <span style="font-size: 34px; font-weight: 800; font-family: 'Courier New', Courier, monospace; letter-spacing: 8px; color: #14532d;">
                                            {{ $otp }}
                                        </span>
                                        <span style="font-size: 12px; color: #16a34a; font-weight: 500; display: block; margin-top: 8px;">
                                            &#x23F1; Valid for 15 minutes
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 16px; font-size: 13px; line-height: 1.5; color: #64748b;">
                                If you did not initiate this request, you can safely ignore this email. Your account remains secure and no changes have been made.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; font-style: italic;">
                                Note: Never share this OTP with anyone, including laboratory administrators or support staff.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 24px; color: #94a3b8; font-size: 11px; line-height: 1.5;">
                            &copy; {{ date('Y') }} {{ $appName ?? 'Soil Testing Laboratory' }}. All rights reserved.<br>
                            Designed &amp; Developed by Umer Bashir
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
