@extends('emails.layout')

@section('title', 'Account Deletion Notice')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Dear {{ $user->name }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    We are writing to inform you that your account with the IT Helpdesk System has been deleted by an administrator.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    This action was taken in accordance with our system administration policies. All your account data and associated information has been permanently removed from our system.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    If you believe this deletion was made in error or if you have any questions about this action, please contact our support team for assistance.
</p>

<!-- Account Details -->
<div style="background-color: #fef2f2; border: 2px solid #dc2626; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #dc2626; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Account Information
    </h2>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Name:</strong> {{ $user->name }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Email:</strong> {{ $user->email }}
    </p>
    @if($user->employee_id)
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0;">
        <strong>Employee ID:</strong> {{ $user->employee_id }}
    </p>
    @endif
</div>

@if($user->phone)
<p style="font-size: 14px; line-height: 1.6; color: #6b7280; margin: 0 0 20px 0;">
    We also sent this notification to {{ $user->phone }} by SMS.
</p>
@endif

<p style="font-size: 14px; color: #6b7280; margin: 0;">
    If you have any questions, please contact our support team.
</p>
@endsection

@section('footer', 'Account management notification')