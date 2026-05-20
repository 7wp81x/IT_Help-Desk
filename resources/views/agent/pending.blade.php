@extends('layouts.guest')

@section('title', 'Account Pending Approval')
@section('header', 'Agent Account Pending Approval')
@section('subheader', 'Your registration has been received and is awaiting admin approval')

@section('content')
<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-8">
        <div class="card shadow-sm" style="border-radius: 1rem; border: none; background: rgba(255,255,255,0.95);">
            <div class="card-body p-5">
                <!-- Pending Icon -->
                <div class="text-center mb-4">
                    <div style="font-size: 4rem; color: #f59e0b;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-center mb-3" style="color: #1f2937; font-weight: 700;">
                    ⏳ Your Agent Account is Pending Admin Approval
                </h2>

                <!-- Message -->
                <div class="text-center mb-4">
                    <p class="mb-3" style="color: #475569; font-size: 1.1rem; line-height: 1.6;">
                        Thank you for registering as a support agent! Your account has been created and is now awaiting approval from our administrators.
                    </p>

                    <div class="alert alert-info" style="border-radius: 0.5rem; border: none; background: #eff6ff; color: #1e40af;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What Happens Next?</strong><br>
                        The admin team will review your account details and approve your access to the agent dashboard. Once approved, you'll receive an email with your Employee ID and full instructions.
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: #374151; font-weight: 600;">Approval Process</h5>
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #065f46; margin: 0;">Registration Completed</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Your agent account has been created</p>
                            </div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-marker">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #d97706; margin: 0;">Awaiting Admin Review</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Admin team is reviewing your account</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #6b7280; margin: 0;">Approval Email</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">You'll receive approval notification and Employee ID</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 style="color: #6b7280; margin: 0;">Full Access Granted</h6>
                                <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Access your agent dashboard and start working</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 0.5rem; margin-bottom: 20px;">
                    <h6 style="color: #065f46; margin-bottom: 10px;"><i class="fas fa-lightbulb me-2"></i>Quick Facts</h6>
                    <ul style="color: #047857; font-size: 0.9rem; margin: 0; padding-left: 20px;">
                        <li>Your account is secure and ready to use once approved</li>
                        <li>Admin approval typically takes 1-3 business days</li>
                        <li>You'll receive an email at <strong>{{ auth()->user()->email }}</strong></li>
                        <li>Your Employee ID will be auto-generated upon approval</li>
                    </ul>
                </div>

                <!-- FAQ -->
                <div class="mb-4">
                    <h5 style="color: #374151; font-weight: 600; margin-bottom: 15px;">Frequently Asked Questions</h5>
                    
                    <div style="margin-bottom: 15px;">
                        <h6 style="color: #1f2937; margin-bottom: 5px;">Can I do anything while waiting?</h6>
                        <p style="color: #6b7280; font-size: 0.9rem; margin: 0;">No, you'll need to wait for admin approval. Once approved, you can log in and access the agent dashboard immediately.</p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <h6 style="color: #1f2937; margin-bottom: 5px;">What if I don't see the approval email?</h6>
                        <p style="color: #6b7280; font-size: 0.9rem; margin: 0;">Check your spam folder. If you still don't find it, contact the administrator.</p>
                    </div>
                    
                    <div>
                        <h6 style="color: #1f2937; margin-bottom: 5px;">Can I use my account before approval?</h6>
                        <p style="color: #6b7280; font-size: 0.9rem; margin: 0;">No, you will not have access to agent features until the admin approves your account.</p>
                    </div>
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