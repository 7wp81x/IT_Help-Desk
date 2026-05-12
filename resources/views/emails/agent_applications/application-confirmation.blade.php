<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#111827;font-family:Inter,system-ui,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 24px 80px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px;text-align:center;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#ffffff;">
                            <h1 style="margin:0;font-size:28px;font-weight:700;">Thanks for applying, {{ $application->first_name }}!</h1>
                            <p style="margin:12px 0 0;font-size:16px;line-height:1.6;">We received your agent application and will review it shortly.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">Your application is now under review. We will contact you if we need additional information or once the review is complete.</p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-radius:16px;background:#f8fafc;padding:20px;">
                                <tr>
                                    <td style="font-size:14px;color:#374151;line-height:1.7;">
                                        <strong>Application details</strong><br>
                                        Name: {{ $application->full_name }}<br>
                                        Email: {{ $application->email }}<br>
                                        Certifications: {{ $application->certifications_list ?: 'None' }}
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:24px 0 0;font-size:16px;line-height:1.75;color:#6b7280;">In the meantime, you can register for an agent account using the same email address you used for this application.</p>
                            <div style="margin-top:24px;text-align:center; display:flex; flex-direction:column; gap:12px; align-items:center;">
                                <a href="{{ route('agent.apply') }}" style="display:inline-flex;align-items:center;justify-content:center;padding:14px 24px;border-radius:9999px;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:600;">View application status</a>
                                <a href="{{ route('register', ['role' => 'agent', 'email' => $application->email]) }}" style="display:inline-flex;align-items:center;justify-content:center;padding:14px 24px;border-radius:9999px;border:2px solid #2563eb;color:#2563eb;text-decoration:none;font-weight:600;">Register as Support Agent</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px;color:#6b7280;font-size:12px;line-height:1.6;text-align:center;">IT Helpdesk System • Professional Support Solution</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>