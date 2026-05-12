<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Helpdesk Team</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#111827;font-family:Inter,system-ui,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 24px 80px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px;text-align:center;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#ffffff;">
                            <h1 style="margin:0;font-size:28px;font-weight:700;">Welcome to the Agent Team!</h1>
                            <p style="margin:12px 0 0;font-size:16px;line-height:1.6;">Your account has been approved and created successfully.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">Hi {{ $user->name }},</p>
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">Your agent account is ready. Use the credentials below to log in and start managing tickets.</p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-radius:16px;background:#f8fafc;padding:20px;">
                                <tr>
                                    <td style="font-size:14px;color:#374151;line-height:1.7;">
                                        <strong>Login email:</strong> {{ $user->email }}<br>
                                        <strong>Password:</strong> {{ $rawPassword }}<br>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:24px 0 16px;font-size:16px;line-height:1.75;color:#6b7280;">For security, please update your password after logging in.</p>
                            <div style="margin-top:24px;text-align:center;">
                                <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;justify-content:center;padding:14px 24px;border-radius:9999px;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:600;">Login to helpdesk</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px;color:#6b7280;font-size:12px;line-height:1.6;text-align:center;">If you did not request this message, please contact support immediately.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>