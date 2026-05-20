@extends('emails.layout')

@section('title', 'Welcome to the Agent Team')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Hi {{ $user->name }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Your agent account is ready. Use the credentials below to log in and start managing tickets.
</p>

<!-- Login Credentials -->
<div style="background-color: #f8fafc; border: 2px solid #2563eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #2563eb; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Login Credentials
    </h2>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Login email:</strong> {{ $user->email }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0;">
        <strong>Password:</strong> {{ $rawPassword }}
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    For security, please update your password after logging in.
</p>

<p style="font-size: 14px; color: #6b7280; margin: 0;">
    If you did not request this message, please contact support immediately.
</p>
@endsection

@section('footer', 'Agent account welcome notification')