@extends('layouts.guest')

@section('title', 'Account Pending Approval')
@section('header', 'Application Under Review')
@section('subheader', 'Your agent application is being processed')

@section('content')
<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-8">
        <div class="card shadow-sm" style="border-radius: 1rem; border: none; background: rgba(255,255,255,0.95);">
            <div class="card-body p-5">
                <!-- Pending Icon -->
                <div class="text-center mb-4">
                    <div style="font-size: 4rem; color: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-center mb-3" style="color: #1f2937; font-weight: 700;">
                    ⏳ Your Agent Application is Pending Approval
                </h2>

                <!-- Message -->
                <div class="text-center mb-4">
                    <p class="mb-3" style="color: #475569; font-size: 1.1rem; line-height: 1.6;">
                        Thank you for your interest in joining our support team. Your application has been submitted and is currently under review by our administrators.
                    </p>

                    <div class="alert alert-info" style="border-radius: 0.5rem; border: none; background: #eff6ff; color: #1e40af;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What happens next?</strong><br>
                        Our team will review your application and qualifications. If approved, you'll receive an email notification with your Agent ID and login instructions.
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: #374151; font-weight: 600;">Review Process</h5>
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #065f46; margin: 0;">Application Submitted</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Your application has been received</p>
                            </div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-marker">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #d97706; margin: 0;">Under Review</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Admin team is reviewing your qualifications</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #6b7280; margin: 0;">Approval Notification</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">You'll receive an email once approved</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #6b7280; margin: 0;">Account Activated</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Access your agent dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="text-center mb-4">
                    <p style="color: #6b7280; font-size: 0.9rem;">
                        <strong>Estimated Review Time:</strong> Usually 2-3 business days<br>
                        <strong>Notification Email:</strong> {{ auth()->user()->email }}
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="text-center">
                    <a href="{{ route('welcome') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; padding: 0.75rem 2rem;">
                        <i class="fas fa-home me-2"></i>Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item.active::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 0;
        bottom: -30px;
        width: 2px;
        background: #10b981;
    }

    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .timeline-item.active .timeline-marker {
        background: #10b981;
        color: white;
    }

    .timeline-content h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }
</style>
@endsection