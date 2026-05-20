@extends('emails.layout')

@section('content')
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #2563eb; font-size: 28px; margin: 0; font-weight: bold;">
            🎉 Congratulations! Your Agent Application Has Been Approved
        </h1>
    </div>

    <!-- Greeting -->
    <div style="margin-bottom: 20px;">
        <p style="font-size: 16px; line-height: 1.6; color: #374151; margin: 0;">
            Dear {{ $user->name }},
        </p>
    </div>

    <!-- Main Content -->
    <div style="margin-bottom: 30px;">
        <p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
            We're excited to inform you that your application to join our support team has been <strong>approved</strong>!
            Welcome to the IT Helpdesk System as a Support Agent.
        </p>

        <p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
            You can now log in using your email address and password. Your Agent ID is shown below for your internal agent profile and verification.
        </p>

        <!-- Agent ID Box -->
        <div style="background-color: #f3f4f6; border: 2px solid #2563eb; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
            <h2 style="color: #2563eb; font-size: 18px; margin: 0 0 10px 0; font-weight: bold;">
                Your Agent ID
            </h2>
            <div style="font-size: 24px; font-weight: bold; color: #1f2937; font-family: monospace;">
                {{ $user->employee_id }}
            </div>
            <p style="margin-top: 15px; color: #475569; font-size: 14px; line-height: 1.6;">
                This is your verification code. Use it together with your password to sign in after approval.
            </p>
        </div>

        <!-- Department Info -->
        @if($user->department)
        <p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 10px;">
            <strong>Department:</strong> {{ $user->department }}
        </p>
        @endif

        <!-- Login Instructions -->
        <div style="background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0;">
            <h3 style="color: #065f46; font-size: 16px; margin: 0 0 10px 0; font-weight: bold;">
                Next Steps
            </h3>
            @if(isset($password) && $password)
                <p style="font-size: 14px; line-height: 1.6; color: #065f46; margin: 0 0 10px 0;">
                    Your agent account has been created automatically. Use the password below to log in, then update it from your profile settings.
                </p>
                <p style="font-size: 16px; line-height: 1.6; color: #111827; font-weight: 700; margin: 0 0 10px 0;">
                    Temporary Password: <span style="font-family: monospace;">{{ $password }}</span>
                </p>
            @else
                <p style="font-size: 14px; line-height: 1.6; color: #065f46; margin: 0 0 10px 0;">
                    Use the <strong>SAME PASSWORD</strong> you created during registration to log in.
                </p>
            @endif
            <p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
                Use your registered email and password to sign in. After logging in, go to the agent dashboard to start handling tickets.
            </p>
        </div>

        <!-- What to Expect -->
        <p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
            Once logged in, you'll have access to your agent dashboard where you can start handling customer tickets and providing excellent support.
        </p>

    </div>

    @if($user->phone)
        <p style="font-size: 14px; line-height: 1.6; color: #6b7280; margin: 0 0 20px 0;">
            We also sent your agent details to {{ $user->phone }} by SMS.
        </p>
    @endif

    <!-- Footer -->
    <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center;">
        <p style="font-size: 14px; color: #6b7280; margin: 0;">
            If you have any questions, please contact our support team.
        </p>
        <p style="font-size: 14px; color: #6b7280; margin: 5px 0 0 0;">
            Best regards,<br>
            The IT Helpdesk Team
        </p>
    </div>
</div>
@endsection