@extends('emails.layout')

@section('title', 'Application Update')

@section('content')
<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Hello {{ $name ?? 'Applicant' }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    Thank you for applying to become an agent at {{ config('app.name', 'IT Helpdesk') }}.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    After reviewing your application, we have decided not to proceed with your candidacy at this time.
</p>

<p style="font-size: 15px; line-height: 1.5; color: #dc2626; background-color: #fee2e2; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
    <strong>Reason:</strong> {{ $reason }}
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 20px;">
    We appreciate your interest and wish you success in your job search.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #374151; margin-bottom: 5px;">
    Sincerely,<br>
    <strong>{{ config('app.name', 'IT Helpdesk') }} Team</strong>
</p>
@endsection

@section('footer', 'Application status update')