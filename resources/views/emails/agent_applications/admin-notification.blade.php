<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Agent Application</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#111827;font-family:Inter,system-ui,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 24px 80px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px;text-align:center;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#ffffff;">
                            <h1 style="margin:0;font-size:28px;font-weight:700;">New agent application submitted</h1>
                            <p style="margin:12px 0 0;font-size:16px;line-height:1.6;">A candidate has applied to join the agent team.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.75;color:#111827;">Review the applicant below and approve or reject the application from the admin portal.</p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-radius:16px;background:#f8fafc;padding:20px;">
                                <tr>
                                    <td style="font-size:14px;color:#374151;line-height:1.7;">
                                        <strong>Applicant details</strong><br>
                                        Name: {{ $application->full_name }}<br>
                                        Email: {{ $application->email }}<br>
                                        Phone: {{ $application->phone ?? 'Not provided' }}<br>
                                        Certifications: {{ $application->certifications_list ?: 'None' }}
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:24px 0 0;font-size:16px;line-height:1.75;color:#6b7280;">Please login to the admin dashboard to review the application in detail.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px;color:#6b7280;font-size:12px;line-height:1.6;text-align:center;">IT Helpdesk System • Admin notifications</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>