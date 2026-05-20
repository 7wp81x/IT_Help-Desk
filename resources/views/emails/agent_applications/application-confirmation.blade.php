@extends('emails.layout')

@section('title', 'Application Received')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Thank you for submitting your agent application. We have received your materials and will begin our review process promptly.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Your application is currently under review by our recruitment team. We will notify you as soon as the evaluation is complete or if we require additional information.
</p>

<!-- Application Details -->
<div style="background-color: #f8fafc; border: 2px solid #2563eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #2563eb; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Application Details
    </h2>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Name:</strong> {{ $application->full_name }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0 0 10px 0;">
        <strong>Email:</strong> {{ $application->email }}
    </p>
    <p style="font-size: 14px; line-height: 1.6; color: #374151; margin: 0;">
        <strong>Certifications:</strong> {{ $application->certifications_list ?: 'None' }}
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    We appreciate your interest in joining our agent network.
</p>

<p style="font-size: 14px; color: #6b7280; margin: 0;">
    If you have any questions, please contact our support team.
</p>
@endsection

@section('footer', 'Application confirmation notification')