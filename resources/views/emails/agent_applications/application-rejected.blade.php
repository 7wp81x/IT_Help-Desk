@extends('emails.layout')

@section('title', 'Application Status Update')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Hi {{ $application->first_name }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    We appreciate your interest, but we are unable to move forward with your application at this time.
</p>

@if($application->admin_notes)
<!-- Admin Note -->
<div style="background-color: #f8fafc; border: 2px solid #2563eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #2563eb; font-size: 18px; margin: 0 0 15px 0; font-weight: bold;">
        Admin Note
    </h2>
    <p style="font-size: 16px; line-height: 1.6; color: #374151; margin: 0;">
        {{ $application->admin_notes }}
    </p>
</div>
@endif

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Thank you again for applying. We encourage you to stay connected for future openings.
</p>

@if($application->phone)
<p style="font-size: 14px; line-height: 1.6; color: #6b7280; margin: 0;">
    A notification was also sent to {{ $application->phone }} if the number was valid.
</p>
@endif
@endsection

@section('footer', 'Application rejection notification')