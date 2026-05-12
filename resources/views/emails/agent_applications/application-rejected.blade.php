<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#111827;font-family:Inter,system-ui,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 24px 80px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px;text-align:center;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#ffffff;">
                            <h1 style="margin:0;font-size:28px;font-weight:700;">Application Update</h1>
                            <p style="margin:12px 0 0;font-size:16px;line-height:1.6;">We have finished reviewing your application.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">Hi {{ $application->first_name }},</p>
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">We appreciate your interest, but we are unable to move forward with your application at this time.</p>
                            @if($application->admin_notes)
                                <div style="border-radius:16px;background:#f8fafc;padding:20px;color:#374151;font-size:14px;line-height:1.7;">
                                    <strong>Admin note:</strong><br>
                                    {{ $application->admin_notes }}
                                </div>
                            @endif
                            <p style="margin:24px 0 0;font-size:16px;line-height:1.75;color:#6b7280;">Thank you again for applying. We encourage you to stay connected for future openings.</p>
                            @if($application->phone)
                                <p style="margin:16px 0 0;font-size:14px;line-height:1.75;color:#6b7280;">A notification was also sent to {{ $application->phone }} if the number was valid.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px;color:#6b7280;font-size:12px;line-height:1.6;text-align:center;">IT Helpdesk System • Recruitment team</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>