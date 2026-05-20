@extends('emails.layout')

@section('title', 'New Agent Application Submitted')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    A candidate has applied to join the agent team.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Review the applicant below and approve or reject the application from the admin portal.
</p>

<!-- Applicant Details -->
<div style="background-color: #f8fafc; border: 2px solid #2563eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #2563eb; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Applicant Details
    </h2>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Name:</strong> {{ $application->full_name }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Email:</strong> {{ $application->email }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Phone:</strong> {{ $application->phone ?? 'Not provided' }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0;">
        <strong>Certifications:</strong> {{ $application->certifications_list ?: 'None' }}
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Please login to the admin dashboard to review the application in detail.
</p>
@endsection

@section('footer', 'Admin notification - new agent application')