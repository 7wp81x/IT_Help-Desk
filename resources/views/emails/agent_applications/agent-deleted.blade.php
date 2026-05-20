@extends('emails.layout')

@section('title', 'Agent Account Deletion Notice')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Dear {{ $user->name }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    We wanted to let you know that your agent account has been deleted by an administrator.
</p>

<!-- Important Notice -->
<div style="background-color: #fef2f2; border: 2px solid #ef4444; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #ef4444; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Important Notice
    </h2>
    <p style="font-size: 16px; line-height: 1.6; color: #991b1b; margin: 0;">
        If you did not request this account deletion, or if you believe this was done in error, please contact support immediately.
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    You will no longer be able to log in as an agent until the account is restored or re-created by the administrator.
</p>

<p style="font-size: 14px; color: #6b7280; margin: 0;">
    If you have any questions, please contact our support team.
</p>
@endsection

@section('footer', 'Agent account deletion notification')
