@component('mail::message')
# Verify Your Email Address

Hello {{ $notifiable->name }},

Thank you for registering with our IT Helpdesk System. Please use the following code to verify your email address:

@component('mail::panel')
# {{ $verificationCode }}
@endcomponent

This code will expire in **30 minutes**.

@component('mail::button', ['url' => route('verification.notice')])
Enter Verification Code
@endcomponent

Or if you prefer, you can [click here]({{ route('verification.notice') }}) to enter the code manually.

If you did not create this account, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
